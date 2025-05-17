import sys
import json
from openai import OpenAI

def main():
    # Read input from PHP
    input_data = json.loads(sys.stdin.read())
    history_sales = input_data.get("history_sales", [])
    forecast_horizon = input_data.get("forecast_horizon", 3)

    client = OpenAI(
        api_key="sk-aca8221d88c241d98cd4ad53cda178bf",
        base_url="https://dashscope-intl.aliyuncs.com/compatible-mode/v1",
    )

    prompt = f"Given the following historical monthly sales data: {history_sales}, predict the next {forecast_horizon} months of sales."

    try:
        completion = client.chat.completions.create(
            model="qwen-plus",
            messages=[
                {'role': 'system', 'content': 'You are an expert in sales forecasting.'},
                {'role': 'user', 'content': prompt}
            ]
        )
        print(json.dumps({"forecast": completion.choices[0].message.content}))
    except Exception as e:
        print(json.dumps({"error": str(e)}))

if __name__ == "__main__":
    main()