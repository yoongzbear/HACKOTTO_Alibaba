import os
from openai import OpenAI

try:
    client = OpenAI(
        # If the environment variable is not configured, replace the following line with your API key: api_key="sk-xxx",
        api_key="sk-aca8221d88c241d98cd4ad53cda178bf",
        base_url="https://dashscope-intl.aliyuncs.com/compatible-mode/v1",
    )

    completion = client.chat.completions.create(
        model="qwen-plus",  # Model list: https://www.alibabacloud.com/help/en/model-studio/getting-started/models
        messages=[
            {'role': 'system', 'content': 'You are a helpful assistant.'},
            {'role': 'user', 'content': 'Who are you?'}
            ]
    )
    print(completion.choices[0].message.content)
except Exception as e:
    print(f"Error message: {e}")
    print("For more information, see: https://www.alibabacloud.com/help/en/model-studio/developer-reference/error-code")