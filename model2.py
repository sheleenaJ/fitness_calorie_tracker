import tensorflow as tf
from tensorflow.keras.applications.mobilenet_v2 import MobileNetV2, preprocess_input
from tensorflow.keras.preprocessing import image
import numpy as np
from flask import Flask, request, jsonify
import openai
import os

# Initialize the OpenAI API client
openai.api_key = 'sk-proj-4LfoUsG1dAuFgX6oyyj7T3BlbkFJOlKEegow9sDppi4cCfzO'

def get_calories(food_item):
    prompt = f"How many calories are in 100 grams of {food_item}? I need only the value. If you not sure about the food or the calorie value plaease give an approximate number thats fine. Only give that number as the output."

    response = openai.ChatCompletion.create(
        model="gpt-3.5-turbo",
        messages=[
            {"role": "system", "content": "You are a helpful assistant."},
            {"role": "user", "content": prompt}
        ],
        max_tokens=50
    )

    message = response['choices'][0]['message']['content'].strip()
    return message

# Load the pre-trained MobileNetV2 model
model = MobileNetV2(weights='imagenet')

def predict_food(image_path):
    img = image.load_img(image_path, target_size=(224, 224))
    img_array = image.img_to_array(img)
    img_array = np.expand_dims(img_array, axis=0)
    img_array = preprocess_input(img_array)

    predictions = model.predict(img_array)
    decoded_predictions = tf.keras.applications.mobilenet_v2.decode_predictions(predictions, top=1)
    food_item = decoded_predictions[0][0][1]  # Extract the food item name
    return food_item

app = Flask(__name__)

# Ensure the uploads directory exists
if not os.path.exists('./uploads'):
    os.makedirs('./uploads')

@app.route('/upload', methods=['POST'])
def upload_image():
    if 'file' not in request.files:
        return jsonify({'error': 'No file part'})

    file = request.files['file']

    if file.filename == '':
        return jsonify({'error': 'No selected file'})

    if file:
        file_path = f"./uploads/{file.filename}"
        file.save(file_path)

        food_item = predict_food(file_path)
        print(food_item)
        calories = get_calories(food_item)

        return jsonify({'food_item': food_item, 'calories_per_100g': calories})

if __name__ == '__main__':
    app.run(host='0.0.0.0', debug=False , port=5000)
