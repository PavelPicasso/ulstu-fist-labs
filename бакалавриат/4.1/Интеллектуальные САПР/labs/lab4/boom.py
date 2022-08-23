import pandas as pd
from sklearn.neural_network import MLPClassifier
from sklearn.linear_model import Perceptron
from sklearn.metrics import mean_squared_error, r2_score, accuracy_score, f1_score
import matplotlib.pyplot as plt

def test(x_train, y_train, x_test, y_test):
    alpha = [0.001, 0.01, 0.03, 1, 3]
    scores_r = []
    scores_r1 = []
    for i in range(len(alpha)):
        reg2 = MLPClassifier(alpha=alpha[i])
        reg2.fit(x_train, y_train)
        y_pred = reg2.predict(x_train)
        y_pred1 = reg2.predict(x_test)
        a = accuracy_score(y_train, y_pred)
        a1 = accuracy_score(y_test, y_pred1)
        scores_r1.append(a1)
        scores_r.append(a)
        print('alpha:', alpha[i], 'accuracy_score(train):', a)
        print('alpha:', alpha[i], 'accuracy_score(test):', a1)

    plt.plot(alpha, scores_r1, alpha, scores_r)
    plt.xlabel('alpha')
    plt.ylabel('scores_r')
    plt.show()



d_train = pd.read_csv('pendigits.tra', sep=',')
y_train = d_train['number']
x_train = d_train.drop(['number'], axis=1)

d_test = pd.read_csv('pendigits.tes', sep=',')
y_test = d_test['number']
x_test = d_test.drop(['number'], axis=1)

mlp = MLPClassifier()
mlp.fit(x_train, y_train)

y_pred = mlp.predict(x_test)
print("MLPClassifier-> Accuracy score: ", accuracy_score(y_test, y_pred))



prt = Perceptron()
prt.fit(x_train, y_train)

y_pred = prt.predict(x_test)
print("Perceptron-> Accuracy score: ", accuracy_score(y_test, y_pred))

test(x_train, y_train, x_test, y_test)