<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PrediksiController extends Controller
{
    public function index()
    {
        $totalPasien = Pasien::count();
        $pasienStroke = Pasien::where('stroke', 1)->count();
        $pasienTidakStroke = Pasien::where('stroke', 0)->count();
        
        $recentData = Pasien::orderBy('created_at', 'desc')->limit(10)->get();
        
        return view('prediksi.index', compact('totalPasien', 'pasienStroke', 'pasienTidakStroke', 'recentData'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls|max:10240',
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        
        $filename = 'data_pasien_' . time() . '.' . $extension;
        $file->move(public_path('uploads'), $filename);
        
        session(['uploaded_file' => $filename]);
        
        return response()->json([
            'success' => true,
            'message' => 'File berhasil diupload!',
            'filename' => $filename
        ]);
    }

    public function importToDatabase(Request $request)
    {
        $filename = session('uploaded_file');
        
        if (!$filename) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada file yang diupload!'
            ], 400);
        }

        $filePath = public_path('uploads/' . $filename);
        
        try {
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            $header = array_map('strtolower', array_map('trim', $rows[0]));
            
            $data = [];
            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];
                if (empty($row[0])) continue;
                
                $rowData = [];
                foreach ($header as $index => $column) {
                    $rowData[$column] = $row[$index] ?? null;
                }
                
                $pasienData = [
                    'gender' => $this->mapGender($rowData['gender'] ?? ''),
                    'age' => floatval($rowData['age'] ?? 0),
                    'hypertension' => intval($rowData['hypertension'] ?? 0),
                    'heart_disease' => intval($rowData['heart_disease'] ?? 0),
                    'ever_married' => $this->mapMarried($rowData['ever_married'] ?? ''),
                    'work_type' => $this->mapWorkType($rowData['work_type'] ?? ''),
                    'residence_type' => $this->mapResidence($rowData['residence_type'] ?? ''),
                    'avg_glucose_level' => floatval($rowData['avg_glucose_level'] ?? 0),
                    'bmi' => $this->parseBmi($rowData['bmi'] ?? null),
                    'smoking_status' => $this->mapSmoking($rowData['smoking_status'] ?? ''),
                    'stroke' => 0,
                ];
                
                $data[] = $pasienData;
            }
            
            Pasien::insert($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengimport ' . count($data) . ' data pasien ke database!',
                'count' => count($data)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function prediksi()
    {
        $pasienBelumDiprediksi = Pasien::where('stroke', 0)->get();
        
        if ($pasienBelumDiprediksi->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data pasien yang perlu diprediksi!'
            ], 400);
        }

        try {
            $tempFile = storage_path('app/temp_prediksi_' . time() . '.json');
            
            $dataJson = $pasienBelumDiprediksi->map(function ($pasien) {
                return [
                    'gender' => $pasien->gender,
                    'age' => $pasien->age,
                    'hypertension' => $pasien->hypertension,
                    'heart_disease' => $pasien->heart_disease,
                    'ever_married' => $pasien->ever_married,
                    'work_type' => $pasien->work_type,
                    'residence_type' => $pasien->residence_type,
                    'avg_glucose_level' => $pasien->avg_glucose_level,
                    'bmi' => $pasien->bmi,
                    'smoking_status' => $pasien->smoking_status,
                ];
            })->values()->toJson();

            file_put_contents($tempFile, $dataJson);

            $pythonScript = base_path('python/prediksi.py');
            $modelPath = base_path('python' . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR);
            
            $pythonExe = base_path('venv' . DIRECTORY_SEPARATOR . 'Scripts' . DIRECTORY_SEPARATOR . 'python.exe');
            
            $descriptorspec = [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w']
            ];
            
            $command = $pythonExe . ' ' . $pythonScript . ' ' . $tempFile . ' ' . $modelPath;
            
            $process = proc_open($command, $descriptorspec, $pipes, dirname(base_path()));
            
            if (is_resource($process)) {
                $output = stream_get_contents($pipes[1]);
                fclose($pipes[0]);
                fclose($pipes[1]);
                fclose($pipes[2]);
                proc_close($process);
            } else {
                @unlink($tempFile);
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to start Python process'
                ], 500);
            }
            
            @unlink($tempFile);
            
            if (empty($output)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error running Python script: No output received'
                ], 500);
            }
            
            $result = json_decode($output, true);
            
            if (!$result || isset($result['error'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . ($result['error'] ?? 'Invalid output')
                ], 500);
            }
            
            if ($result && isset($result['predictions'])) {
                $predictions = $result['predictions'];
                $ids = $pasienBelumDiprediksi->pluck('id')->toArray();
                
                foreach ($ids as $index => $pasienId) {
                    if (isset($predictions[$index])) {
                        $pasien = Pasien::find($pasienId);
                        if ($pasien) {
                            $pasien->stroke = $predictions[$index];
                            $pasien->save();
                        }
                    }
                }
            }
            
            $strokeCount = $result['stroke_count'] ?? 0;
            $tidakStrokeCount = $result['tidak_stroke_count'] ?? 0;
            
            return response()->json([
                'success' => true,
                    'message' => 'Prediksi berhasil!',
                    'stroke' => $result['stroke_count'] ?? 0,
                    'tidak_stroke' => $result['tidak_stroke_count'] ?? 0,
                    'total' => count($pasienBelumDiprediksi)
                ]);
                
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStats()
    {
        $totalPasien = Pasien::count();
        $pasienStroke = Pasien::where('stroke', 1)->count();
        $pasienTidakStroke = Pasien::where('stroke', 0)->count();
        
        return response()->json([
            'total' => $totalPasien,
            'stroke' => $pasienStroke,
            'tidak_stroke' => $pasienTidakStroke
        ]);
    }

    public function getDataTerbaru()
    {
        $data = Pasien::orderBy('created_at', 'desc')->limit(10)->get();
        
        return response()->json($data);
    }

    public function hapusData()
    {
        Pasien::truncate();
        
        return response()->json([
            'success' => true,
            'message' => 'Semua data pasien berhasil dihapus!'
        ]);
    }

    private function mapGender($value)
    {
        $value = strtolower(trim($value));
        if (in_array($value, ['male', 'laki-laki', 'm'])) return 'Male';
        if (in_array($value, ['female', 'perempuan', 'f'])) return 'Female';
        return 'Other';
    }

    private function mapMarried($value)
    {
        $value = strtolower(trim($value));
        if (in_array($value, ['yes', 'ya', 'sudah'])) return 'Yes';
        return 'No';
    }

    private function mapWorkType($value)
    {
        $value = strtolower(trim($value));
        $mapping = [
            'private' => 'Private',
            'self-employed' => 'Self-employed',
            'govt_job' => 'Govt_job',
            'children' => 'children',
            'never_worked' => 'Never_worked',
        ];
        return $mapping[$value] ?? 'Private';
    }

    private function mapResidence($value)
    {
        $value = strtolower(trim($value));
        if (in_array($value, ['urban', 'kota'])) return 'Urban';
        return 'Rural';
    }

    private function mapSmoking($value)
    {
        $value = strtolower(trim($value));
        $mapping = [
            'smokes' => 'smokes',
            'formerly smoked' => 'formerly smoked',
            'never smoked' => 'never smoked',
            'unknown' => 'Unknown',
        ];
        return $mapping[$value] ?? 'Unknown';
    }

    private function parseBmi($value)
    {
        if ($value === null || $value === '' || $value === 'N/A') {
            return null;
        }
        return floatval($value);
    }
}
