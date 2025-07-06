from flask import Flask, request, jsonify
import os
import json
import cv2
import numpy as np
from langchain_google_genai import ChatGoogleGenerativeAI
from dotenv import load_dotenv
load_dotenv()



app = Flask(__name__)

# Allow cross-origin requests for Laravel
from flask_cors import CORS
CORS(app)

# Load API Key
API_KEY = os.getenv("GEMINI_API_KEY", "AIzaSyB511TdSQhd2wSfbB_QyEadU6m9z01ClgA")
if not API_KEY:
    raise ValueError("GEMINI_API_KEY is not set in the environment.")
LLM = ChatGoogleGenerativeAI(api_key=API_KEY, model="gemini-1.5-pro", temperature=0.7)

# Generate reflection question
@app.route("/generate-question", methods=["POST"])
def generate_question():
    data = request.json
    app_name = data.get("app_name")
    usage_time = data.get("usage_time")

    prompt = f"You are a digital well-being assistant. The user has exceeded screen time for {app_name} after using it for {usage_time}. Generate a question to help the user reflect."

    try:
        response = LLM.invoke(prompt)
        return jsonify({"question": response.content.strip()}), 200
    except Exception as e:
        print(f"[Fallback] Gemini error in /generate-question: {e}")
        fallback_question = "How did using this app for so long make you feel, and was it worth your time?"
        return jsonify({"question": fallback_question, "fallback": True}), 200

# Analyze emotion
@app.route('/analyze-emotion', methods=['POST'])
def analyze_emotion():
    try:
        data = request.get_json()
        user_response = data.get("response", "").strip() if data else ""

        if not user_response:
            return jsonify({"error": "No response text provided"}), 400

        prompt = f"""Your job is to classify the emotional tone of the following text. 

Text: "{user_response}"
Respond ONLY in this exact JSON format (no commentary, no Markdown, no code blocks):
{{
  "emotion": "one-word emotion like 'fear', 'anger', 'joy', 'sadness', etc.",
  "category": "positive" | "negative" | "neutral"
}}"""
        response = LLM.invoke(prompt)
        raw_output = response.content.strip()

        if raw_output.startswith("```json"):
            raw_output = raw_output.replace("```json", "").replace("```", "").strip()

        parsed = json.loads(raw_output)

        if "emotion" not in parsed or "category" not in parsed:
            raise ValueError("Missing keys in AI response.")

        return jsonify(parsed), 200

    except Exception as e:
        print(f"[Fallback] Gemini error in /analyze-emotion: {e}")
        fallback_emotion = {
            "emotion": "confused",
            "category": "neutral",
            "fallback": True
        }
        return jsonify(fallback_emotion), 200


# Suggest alternative activity
@app.route("/suggest-activity", methods=["POST"])
def suggest_activity():
    data = request.json
    emotion = data.get("emotion")

    prompt = f"Suggest an activity for someone feeling '{emotion}'. Be specific."
    try:
        response = LLM.invoke(prompt)
        return jsonify({"suggestion": response.content.strip()}), 200
    except Exception as e:
        print(f"[Fallback] Gemini error in /suggest-activity: {e}")
        fallback_activity = "Try going for a short walk or doing a breathing exercise to reset your mind."
        return jsonify({"suggestion": fallback_activity, "fallback": True}), 200

# Full Insight Endpoint
@app.route("/full-insight", methods=["POST"])
def full_insight():
    try:
        data = request.get_json()
        app_name = data.get("app_name")
        usage_time = data.get("usage_time")
        user_response = data.get("response")

        if not all([app_name, usage_time, user_response]):
            return jsonify({"error": "Missing required fields"}), 400

        # --- Fallback defaults
        fallback_question = "What did you enjoy most while using your device today?"
        fallback_emotion_data = {"emotion": "neutral", "category": "neutral"}
        fallback_suggestion = "Take a break, stretch, or listen to relaxing music."

        try:
            reflection_prompt = f"You are a digital well-being assistant. The user has exceeded screen time for {app_name} after using it for {usage_time}. Generate a question to help the user reflect."
            reflection_response = LLM.invoke(reflection_prompt).content.strip()
        except Exception as e:
            print(f"[Fallback] Reflection failed: {e}")
            reflection_response = fallback_question

        try:
            emotion_prompt = f"""Your job is to classify the emotional tone of the following text. 

Text: "{user_response}"

Respond ONLY in this exact JSON format (no commentary, no Markdown, no code blocks):

{{
  "emotion": "one-word emotion like 'fear', 'anger', 'joy', 'sadness', etc.",
  "category": "positive" | "negative" | "neutral"
}}"""
            emotion_raw = LLM.invoke(emotion_prompt).content.strip()
            if emotion_raw.startswith("```json"):
                emotion_raw = emotion_raw.replace("```json", "").replace("```", "").strip()

            emotion_data = json.loads(emotion_raw)
            if "emotion" not in emotion_data or "category" not in emotion_data:
                raise ValueError("Missing keys")

        except Exception as e:
            print(f"[Fallback] Emotion analysis failed: {e}")
            emotion_data = fallback_emotion_data

        suggestion = None
        if emotion_data.get("category") == "negative":
            try:
                suggestion_prompt = f"Suggest an activity for someone feeling '{emotion_data.get('emotion')}'. Be specific."
                suggestion = LLM.invoke(suggestion_prompt).content.strip()
            except Exception as e:
                print(f"[Fallback] Activity suggestion failed: {e}")
                suggestion = fallback_suggestion

        return jsonify({
            "question": reflection_response,
            "emotion_analysis": emotion_data,
            "suggestion": suggestion,
            "allow_limit": emotion_data.get("category") in ["positive", "neutral"]
        }), 200

    except Exception as e:
        return jsonify({"error": f"Failed to process insight: {str(e)}"}), 500




# Face distance config
face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')
SAFE_DISTANCE_THRESHOLD = 100  # You can adjust this value

@app.route('/analyze-distance', methods=['POST'])
def analyze_distance():
    try:
        if 'frame' not in request.files:
            return jsonify({'error': 'No frame provided'}), 400

        file = request.files['frame']
        file_bytes = np.frombuffer(file.read(), np.uint8)
        img = cv2.imdecode(file_bytes, cv2.IMREAD_COLOR)

        gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
        faces = face_cascade.detectMultiScale(gray, scaleFactor=1.3, minNeighbors=5)

        if len(faces) == 0:
            return jsonify({'status': 'no_face_detected'}), 200

        largest_face = max(faces, key=lambda box: box[2] * box[3])
        (x, y, w, h) = largest_face
        face_width = w

        if face_width >= SAFE_DISTANCE_THRESHOLD:
            return jsonify({'status': 'too_close', 'face_width': face_width}), 200
        else:
            return jsonify({'status': 'safe', 'face_width': face_width}), 200

    except Exception as e:
        return jsonify({'error': str(e)}), 500

if __name__ == "__main__":
    app.run(debug=True)
