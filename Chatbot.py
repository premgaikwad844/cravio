from flask import Flask, request, jsonify
import requests
import os
from flask_cors import CORS
from dotenv import load_dotenv

# QUICKSTART:
# 1. Create a .env file in this directory with: OPENROUTER_API_KEY=your_actual_key_here
# 2. pip install -r requirements.txt
# 3. python Chatbot.py
# 4. The server will run on http://127.0.0.1:5000/

load_dotenv()

app = Flask(__name__)
CORS(app)

OPENROUTER_API_KEY = os.getenv("OPENROUTER_API_KEY")

@app.route('/health', methods=['GET'])
def health():
    if not OPENROUTER_API_KEY:
        return jsonify({"status": "error", "message": "API key missing"}), 500
    return jsonify({"status": "ok"})

def is_food_request(user_input):
    # Only block if user is asking for a dish/food suggestion
    keywords = [
        "suggest a dish", "recommend a dish", "recommend food", "suggest food", "what should I eat", "find me a dish", "give me a dish", "show me a dish", "best food", "best dish"
    ]
    for word in keywords:
        if word in user_input.lower():
            return True
    return False

def is_malicious_request(user_input):
    """
    Checks for common AI attack vectors and sensitive requests.
    Returns a tuple (is_malicious, reason) where is_malicious is True if blocked, and reason is a string.
    """
    if not user_input:
        return False, None
    lower = user_input.lower()
    # Prompt Injection / Jailbreak / DAN / Prompt Leaking
    prompt_injection_keywords = [
        'ignore previous instructions', 'disregard previous instructions', 'forget previous instructions',
        'act as', 'pretend to be', 'you are now', 'jailbreak', 'dan', 'do anything now',
        'system prompt', 'bypass', 'unfiltered', 'developer mode', 'root access', 'simulate',
        'prompt injection', 'prompt leak', 'what were you told', 'what is your prompt',
        'repeat this prompt', 'show me your instructions', 'show me your system prompt',
        'output the prompt', 'reveal your prompt', 'prompt leaking', 'prompt chain', 'prompt chaining',
        'as an ai', 'as a language model', 'as an assistant', 'as a chatbot',
    ]
    # Source code / Sensitive info
    sensitive_keywords = [
        'source code', 'php file', 'python file', 'show me the code', 'backend code', 'script',
        'config.php', 'database password', 'admin credentials', '.env', 'api key', 'secret key',
        'credentials', 'password', 'private key', 'access token', 'server details', 'ftp',
        'sql', 'database dump', 'schema', 'table structure', 'list all users',
    ]
    # Malicious code / Adversarial / Phishing
    code_attack_keywords = [
        'malicious code', 'write a virus', 'write malware', 'bypass security', 'exploit',
        'phishing email', 'phishing message', 'fake login', 'steal data', 'steal password',
        'csrf', 'xss', 'sql injection', 'command injection', 'remote code execution',
    ]
    # Toxicity / Offensive
    toxicity_keywords = [
        'offensive', 'toxic', 'hate speech', 'racist', 'sexist', 'abusive', 'harmful',
        'generate hate', 'generate violence', 'insult', 'swear', 'curse',
    ]
    # Overload/Abuse
    abuse_keywords = [
        'flood', 'spam', 'crash', 'denial of service', 'dos', 'overload', 'infinite loop',
    ]
    # Combine all
    all_keywords = prompt_injection_keywords + sensitive_keywords + code_attack_keywords + toxicity_keywords + abuse_keywords
    for word in all_keywords:
        if word in lower:
            return True, word
    # Adversarial input (very basic check)
    if any(ord(c) > 127 for c in user_input):  # suspicious unicode
        return True, 'adversarial unicode'
    return False, None

@app.route('/chat', methods=['POST'])
def chat():
    if not OPENROUTER_API_KEY:
        return jsonify({"reply": "Backend error: API key missing."}), 500
    data = request.get_json()
    user_input = data.get('message')

    # Block only direct food/dish requests
    if is_food_request(user_input):
        polite_reply = (
            "I'm here to help! To find dishes that match your requirements, "
            "please use the search and filters on our website. "
            "You can explore categories, apply dietary filters, and more to discover the perfect meal for you!"
        )
        return jsonify({"reply": polite_reply})

    # Block malicious/attack/jailbreak requests
    is_mal, reason = is_malicious_request(user_input)
    if is_mal:
        return jsonify({"reply": "Sorry, I can't assist with that request."}), 403

    # Website info questions
    website_questions = [
        "what is this website", "what's this website", "tell me about this website", "describe this website", "about this website", "what is cravio", "what's cravio", "about cravio", "what does this website do", "purpose of this website"
    ]
    how_works_questions = [
        "how does this website work", "how does cravio work", "explain how this website works", "explain how cravio works", "how does it work", "how does the website work", "how does your website work"
    ]
    if any(q in user_input.lower() for q in website_questions):
        return jsonify({"reply": "This website is Cravio, your smart food discovery platform! Here, you can explore a wide variety of dishes, filter by cuisine, dietary preferences, and more. Use the search bar and filters to find your next favorite meal quickly and easily."})
    if any(q in user_input.lower() for q in how_works_questions):
        return jsonify({"reply": "Cravio works by letting you search and discover food dishes from a large database. You can use the search bar to type in dish names or keywords, and apply filters for cuisine, dietary preferences, state, city, and more. The website then shows you matching dishes with details and images, helping you find the perfect meal for your taste and needs!"})

    # Custom answer for 'how do I use the search bar'
    if "how do i use the search bar" in user_input.lower() or "how to use the search bar" in user_input.lower():
        return jsonify({"reply": "To use the search bar, simply type the name or type of food you want and press Enter. You can also use filters to narrow down your results by category, dietary preference, and more."})

    # If not a website/search/food question, politely refuse
    allowed_topics = website_questions + ["how do i use the search bar", "how to use the search bar"]
    if not any(q in user_input.lower() for q in allowed_topics):
        return jsonify({"reply": "I'm here to help you with information about this website and how to use it. Please ask about the website, food search, or using the search bar!"})

    headers = {
        "Authorization": f"Bearer {OPENROUTER_API_KEY}",
        "Content-Type": "application/json"
    }

    payload = {
        "model": "openai/gpt-3.5-turbo",  # You can change to other models supported by OpenRouter
        "messages": [
            {"role": "system", "content": "You are a helpful assistant."},
            {"role": "user", "content": user_input}
        ]
    }

    try:
        response = requests.post("https://openrouter.ai/api/v1/chat/completions", headers=headers, json=payload)
        response.raise_for_status()
        response_json = response.json()
        message = response_json.get('choices', [{}])[0].get('message', {}).get('content', '')
        if not message:
            message = "Sorry, I couldn't get a response from OpenRouter."
        return jsonify({"reply": message})
    except Exception as e:
        return jsonify({"reply": f"Backend error: {str(e)}"}), 500

if __name__ == '__main__':
    app.run(debug=True) 