import sys
import json
import pickle
import numpy as np
import os

def preprocess_data(data):
    gender_map = {'male': 0, 'female': 1, 'other': 2}
    ever_married_map = {'no': 0, 'yes': 1}
    work_type_map = {'govt_job': 0, 'never_worked': 1, 'private': 2, 'self-employed': 3, 'children': 4}
    residence_type_map = {'rural': 0, 'urban': 1}
    smoking_map = {'formerly smoked': 0, 'never smoked': 1, 'smokes': 2, 'unknown': 3}
    
    processed = []
    for row in data:
        try:
            gender = gender_map.get(str(row['gender']).lower(), 2)
            age = float(row['age'])
            hypertension = int(row['hypertension'])
            heart_disease = int(row['heart_disease'])
            ever_married = ever_married_map.get(str(row['ever_married']).lower(), 0)
            work_type = work_type_map.get(str(row['work_type']).lower(), 2)
            residence_type = residence_type_map.get(str(row['residence_type']).lower(), 1)
            avg_glucose_level = float(row['avg_glucose_level'])
            bmi = float(row['bmi']) if row['bmi'] is not None else 0.0
            smoking_status = smoking_map.get(str(row['smoking_status']).lower(), 3)
        except (ValueError, KeyError) as e:
            gender = 2
            age = 0
            hypertension = 0
            heart_disease = 0
            ever_married = 0
            work_type = 2
            residence_type = 1
            avg_glucose_level = 0
            bmi = 0
            smoking_status = 3
        
        processed.append([
            gender, age, hypertension, heart_disease, ever_married,
            work_type, residence_type, avg_glucose_level, bmi, smoking_status
        ])
    
    return np.array(processed)

def main():
    if len(sys.argv) < 3:
        print(json.dumps({'error': 'Invalid arguments'}))
        sys.exit(1)
    
    input_file = sys.argv[1]
    model_path = sys.argv[2]
    
    # Remove quotes if present
    input_file = input_file.strip('"').strip("'")
    model_path = model_path.strip('"').strip("'")
    
    # Handle Windows path issues
    input_file = os.path.abspath(input_file)
    model_path = os.path.abspath(model_path)
    
    try:
        with open(input_file, 'r') as f:
            data = json.load(f)
    except Exception as e:
        print(json.dumps({'error': f'Error reading input file: {str(e)}'}))
        sys.exit(1)
    
    model_file = os.path.join(model_path, 'svm_model.pkl')
    scaler_file = os.path.join(model_path, 'scaler.pkl')
    
    try:
        with open(model_file, 'rb') as f:
            model = pickle.load(f)
        
        with open(scaler_file, 'rb') as f:
            scaler = pickle.load(f)
    except Exception as e:
        print(json.dumps({'error': f'Error loading model: {str(e)}'}))
        sys.exit(1)
    
    try:
        X = preprocess_data(data)
        X_scaled = scaler.transform(X)
        
        predictions = model.predict(X_scaled)
        
        stroke_count = int(np.sum(predictions == 1))
        tidak_stroke_count = int(np.sum(predictions == 0))
        
        result = {
            'predictions': predictions.tolist(),
            'stroke_count': stroke_count,
            'tidak_stroke_count': tidak_stroke_count
        }
        
        print(json.dumps(result))
    except Exception as e:
        print(json.dumps({'error': f'Error during prediction: {str(e)}'}))
        sys.exit(1)

if __name__ == '__main__':
    main()
