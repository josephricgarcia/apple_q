from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
import pickle
import numpy as np

app = FastAPI()

# Define input data model
class AppleData(BaseModel):
    size: float
    weight: float
    sweetness: float
    crunchiness: float
    juiciness: float
    ripeness: float
    acidity: float

# Load models
def load_models():
    try:
        with open('rf_model.pkl', 'rb') as f:
            model = pickle.load(f)
        with open('scaler.pkl', 'rb') as f:
            scaler = pickle.load(f)
        return model, scaler
    except Exception as e:
        raise Exception(f"Error loading models: {str(e)}")

model, scaler = load_models()

@app.post("/predict")
async def predict(data: AppleData):
    try:
        # Convert input to numpy array
        input_array = np.array([[
            data.size,
            data.weight,
            data.sweetness,
            data.crunchiness,
            data.juiciness,
            data.ripeness,
            data.acidity
        ]])
        
        # Scale input
        input_scaled = scaler.transform(input_array)
        
        # Make prediction
        prediction = model.predict(input_scaled)[0]
        confidence = model.predict_proba(input_scaled)[0][prediction] * 100
        
        return {
            "prediction": int(prediction),
            "confidence": float(confidence)
        }
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Prediction error: {str(e)}")