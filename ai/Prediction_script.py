import joblib
import pandas as pd
import numpy as np

def predict_purchase_intention(user_data):
    # 1. تحميل النموذج والأدوات المحفوظة
    model = joblib.load('shopper_model.pkl')
    le_month = joblib.load('le_month.pkl')
    le_visitor = joblib.load('le_visitor.pkl')

    # 2. تحويل بيانات المستخدم لتناسب النموذج
    # إنشاء DataFrame بصف واحد
    input_df = pd.DataFrame([user_data])
    
    # معالجة النصوص بنفس الطريقة التي تمت أثناء التدريب
    try:
        input_df['Month'] = le_month.transform(input_df['Month'])
        input_df['VisitorType'] = le_visitor.transform(input_df['VisitorType'])
        input_df['Weekend'] = input_df['Weekend'].astype(int)
    except ValueError as e:
        return f"خطأ في البيانات المدخلة: {e}"

    # 3. التنبؤ
    prediction = model.predict(input_df)
    probability = model.predict_proba(input_df) # نسبة الاحتمالية

    # 4. صياغة النتيجة
    result = "سيشتري (Revenue=True)" if prediction[0] == 1 else "لن يشتري (Revenue=False)"
    confidence = probability[0][1] if prediction[0] == 1 else probability[0][0]
    
    return {
        "Prediction": result,
        "Confidence": f"{confidence * 100:.2f}%",
        "Action_Required": "Send Offer!" if prediction[0] == 1 else "Ignore"
    }

# ==========================================
# محاكاة: بيانات قادمة من التطبيق أو الموقع
# ==========================================

# مثال: زائر قضى وقتاً طويلاً في صفحات المنتجات (PageValues عالية)
current_user = {
    'Administrative': 0,
    'Administrative_Duration': 0,
    'Informational': 0,
    'Informational_Duration': 0,
    'ProductRelated': 15,       # زار 15 صفحة منتج
    'ProductRelated_Duration': 600, # قضى 10 دقائق
    'BounceRates': 0.0,         # لم يرتد
    'ExitRates': 0.01,          # معدل خروج منخفض جداً
    'PageValues': 50.0,         # قيمة الصفحة عالية (مؤشر قوي للشراء)
    'SpecialDay': 0.0,
    'Month': 'Nov',             # شهر نوفمبر
    'OperatingSystems': 2,
    'Browser': 2,
    'Region': 1,
    'TrafficType': 2,
    'VisitorType': 'Returning_Visitor',
    'Weekend': True
}

# تشغيل التوقع
decision = predict_purchase_intention(current_user)
print("قرار النموذج:", decision)