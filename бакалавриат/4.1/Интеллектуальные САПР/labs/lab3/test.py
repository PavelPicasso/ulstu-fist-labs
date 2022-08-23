import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
from sklearn import datasets, linear_model
from sklearn.pipeline import Pipeline
from sklearn.preprocessing import PolynomialFeatures, OneHotEncoder
from sklearn.linear_model import LinearRegression
from sklearn.model_selection import cross_val_score
from sklearn.ensemble import RandomForestClassifier, RandomForestRegressor, ExtraTreesRegressor, GradientBoostingRegressor
from sklearn.metrics import mean_squared_error, r2_score, accuracy_score, f1_score

def test_2(X_train, y_train, X_test, y_test):
    # pipeline
    degrees = [1]
    scores = []
    for i in range(len(degrees)):
        polynomial_features = PolynomialFeatures(degree=degrees[i],
                                                 include_bias=False)
        regr = linear_model.LinearRegression()
        pipeline = Pipeline([("polynomial_features", polynomial_features),
                             ("linear_regression", regr)])
        pipeline.fit(X_train, y_train)

        y_pred = pipeline.predict(X_test)
        error = mean_squared_error(y_test, y_pred)

        print("Degree: ", degrees[i])
        print("MSE = ", error)
        scores.append(r2_score(y_test, y_pred))
        print("Accuracy score: ", r2_score(y_test, y_pred))

def test_3(X_train, y_train, X_test, y_test):
    alpha = [0.001, 0.003, 0.01, 0.03, 1, 3]
    scores_r = []
    scores_r1 = []
    for i in range(len(alpha)):
        reg2 = linear_model.Ridge(alpha=alpha[i])
        reg2.fit(X_train, y_train)
        y_pred = reg2.predict(X_train)
        y_pred1 = reg2.predict(X_test)
        a = r2_score(y_train, y_pred)
        a1 = r2_score(y_test, y_pred1)
        scores_r1.append(a1)
        scores_r.append(a)
        print('alpha:', alpha[i], 'r2_score(train):', a)
        print('alpha:', alpha[i], 'r2_score(test):', a1)

    plt.plot(alpha, scores_r1, alpha, scores_r)
    plt.xlabel('alpha')
    plt.ylabel('scores_r')
    plt.show()




df = pd.read_csv('household_power_consumption.txt', sep=';',
                 parse_dates={'dt' : ['Date', 'Time']}, infer_datetime_format=True,
                 low_memory=False, na_values=['nan','?'], index_col='dt')
# print(df.dtypes)
# filling nan with mean in any columns

for j in range(0,7):
        df.iloc[:,j]=df.iloc[:,j].fillna(df.iloc[:,j].mean())

# Split the targets into training/testing sets
y_train = df['Global_active_power'][:-400000]
y_test = df['Global_active_power'][-400000:]

df.drop(['Global_active_power'], axis=1, inplace=True)

# Split the data into training/testing sets
X_train = df[:-400000]
X_test = df[-400000:]

# # Create linear regression object
regr = linear_model.LinearRegression()
#
# Train the model using the training sets
regr.fit(X_train, y_train)

# Make predictions using the testing set
y_pred = regr.predict(X_test)

# # The coefficients
# print('Coefficients: \n', regr.coef_)
# The mean squared error
print("Mean squared error: %.2f" % mean_squared_error(y_test, y_pred))
# Explained variance score: 1 is perfect prediction
print('Variance score: %.2f' % r2_score(y_test, y_pred))
#test_2(X_train, y_train, X_test, y_test)
#test_3(X_train, y_train, X_test, y_test)