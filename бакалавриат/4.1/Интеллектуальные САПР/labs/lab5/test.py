import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
from sklearn.cluster import KMeans, Birch, MiniBatchKMeans
from sklearn import preprocessing
from sklearn.metrics.cluster import homogeneity_score, completeness_score, v_measure_score

def Kmeans(x_train, y_train):
    inits = [1, 2, 3, 5, 10, 20, 50]
    state = [10, 100, 300, 500, 1000, 1500, 2000, 3000, 5000, 10000]
    scores_r1 = []
    for i in range(len(state)):
        reg1 = KMeans(init="k-means++", n_clusters=4, random_state=state[i])
        reg1.fit(x_train, y_train)
        y_pred1 = reg1.predict(x_train)
        a1 = v_measure_score(y_train, y_pred1)
        scores_r1.append(a1)
        print('state:', state[i], 'v_measure_score:', a1)
    plt.plot(state, scores_r1)
    plt.xlabel('state')
    plt.ylabel('v_measure_score')
    plt.show()

    scores_r2 = []
    for i in range(len(inits)):
        reg2 = KMeans(init="k-means++", n_clusters=4, n_init=inits[i])
        reg2.fit(x_train, y_train)
        y_pred = reg2.predict(x_train)
        a2 = v_measure_score(y_train, y_pred)
        scores_r2.append(a2)
        print('inits:', inits[i], 'v_measure_score:', a2)
    plt.plot(inits, scores_r2)
    plt.xlabel('inits')
    plt.ylabel('v_measure_score')
    plt.show()

def birch(x_train, y_train):
    thresholds = [0.1, 0.2, 0.5, 1, 1.5, 2]
    b_factors = [5, 10, 15, 30, 50, 70, 100, 150, 300, 500]
    scores_r1 = []
    for i in range(len(thresholds)):
        reg1 = Birch(n_clusters=4, threshold=thresholds[i])
        reg1.fit(x_train, y_train)
        y_pred1 = reg1.predict(x_train)
        a1 = v_measure_score(y_train, y_pred1)
        scores_r1.append(a1)
        print('state:', thresholds[i], 'v_measure_score:', a1)
    plt.plot(thresholds, scores_r1)
    plt.xlabel('threshold')
    plt.ylabel('v_measure_score')
    plt.show()

    scores_r2 = []
    for i in range(len(b_factors)):
        reg2 = Birch(n_clusters=4,  branching_factor=b_factors[i])
        reg2.fit(x_train, y_train)
        y_pred = reg2.predict(x_train)
        a2 = v_measure_score(y_train, y_pred)
        scores_r2.append(a2)
        print('b_factors:', b_factors[i], 'v_measure_score:', a2)
    plt.plot(b_factors, scores_r2)
    plt.xlabel('b_factors')
    plt.ylabel('v_measure_score')
    plt.show()

def mini_batch_KMeans(x_train, y_train):
    inits = [1, 2, 3, 5, 10, 20, 50]
    state = [10, 100, 300, 500, 1000, 1500, 2000, 3000, 5000, 10000]
    scores_r1 = []
    for i in range(len(state)):
        reg1 = MiniBatchKMeans(init="k-means++", n_clusters=4, random_state=state[i])
        reg1.fit(x_train, y_train)
        y_pred1 = reg1.predict(x_train)
        a1 = v_measure_score(y_train, y_pred1)
        scores_r1.append(a1)
        print('state:', state[i], 'v_measure_score:', a1)
    plt.plot(state, scores_r1)
    plt.xlabel('state')
    plt.ylabel('v_measure_score')
    plt.show()

    scores_r2 = []
    for i in range(len(inits)):
        reg2 = MiniBatchKMeans(init="k-means++", n_clusters=4, n_init=inits[i])
        reg2.fit(x_train, y_train)
        y_pred = reg2.predict(x_train)
        a2 = v_measure_score(y_train, y_pred)
        scores_r2.append(a2)
        print('inits:', inits[i], 'v_measure_score:', a2)
    plt.plot(inits, scores_r2)
    plt.xlabel('inits')
    plt.ylabel('v_measure_score')
    plt.show()

df = pd.read_csv('Training_Data.csv', sep=';')
class_distribution_score = [50, 130, 129, 122]
class_distribution = ['very_low', 'High', 'Low', 'Middle']
df= df.replace(to_replace=class_distribution, value =class_distribution_score)

y_train = df['UNS']
x_train = df.drop(['UNS'], axis=1)

# Kmeans(x_train, y_train)
# birch(x_train, y_train)
mini_batch_KMeans(x_train, y_train)