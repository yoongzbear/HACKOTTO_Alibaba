import json
from prophet import Prophet
import pandas as pd

def handler(event, context):
    data = json.loads(event.body.read())
    history_sales = data.get('history_sales')
    horizon = data.get('forecast_horizon', 3)

    df = pd.DataFrame({
        'ds': pd.date_range(start='2024-01-01', periods=len(history_sales), freq='M'),
        'y': history_sales
    })

    model = Prophet()
    model.add_country_holidays(country_name='US')
    model.fit(df)
    future = model.make_future_dataframe(periods=horizon, freq='M')
    forecast = model.predict(future)

    result = {
        'forecast': forecast['yhat'].tail(horizon).round(0).astype(int).tolist()
    }

    return {
        "statusCode": 200,
        "body": json.dumps(result)
    }