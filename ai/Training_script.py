import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier
from sklearn.preprocessing import LabelEncoder
from sklearn.metrics import accuracy_score, classification_report
import joblib

# 1. تحميل البيانات
# تأكد من أن اسم الملف صحيح وموجود بجوار الكود
try:
    df = pd.read_csv('online_shoppers_intention.csv')
except FileNotFoundError:
    print("خطأ: لم يتم العثور على ملف البيانات. تأكد من اسمه ومساره.")
    exit()

# 2. المعالجة المسبقة (Preprocessing)
le_month = LabelEncoder()
df['Month'] = le_month.fit_transform(df['Month'])

le_visitor = LabelEncoder()
df['VisitorType'] = le_visitor.fit_transform(df['VisitorType'])

# تحويل القيم البوليانية
df['Weekend'] = df['Weekend'].astype(int)
df['Revenue'] = df['Revenue'].astype(int)

# 3. تحديد المدخلات والمخرجات
X = df.drop('Revenue', axis=1)
y = df['Revenue']

# 4. تقسيم البيانات
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# ========================================================
# التغيير هنا: بدلاً من استخدام مكتبة خارجية للتوازن
# استخدمنا class_weight='balanced' داخل النموذج نفسه
# ========================================================

# 5. بناء النموذج
# class_weight='balanced': يجعل النموذج يعطي اهتماماً أكبر للفئة القليلة (المشترين)
model = RandomForestClassifier(n_estimators=100, class_weight='balanced', random_state=42)
model.fit(X_train, y_train)

# 6. التقييم
predictions = model.predict(X_test)
print("Accuracy:", accuracy_score(y_test, predictions))
print("\nReport:\n", classification_report(y_test, predictions))

# 7. حفظ النموذج
joblib.dump(model, 'shopper_model.pkl')
joblib.dump(le_month, 'le_month.pkl')
joblib.dump(le_visitor, 'le_visitor.pkl')

print("Model saved successfully (shopper_model.pkl)!")