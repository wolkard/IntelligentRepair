#-*- coding: UTF-8 -*-
import numpy as np
import json
from sklearn.preprocessing import StandardScaler
from sklearn.svm import SVC
import pickle

#定义类
class WifiInfo(object):
    def __init__(self, X, y, macAddrDict, roomDict):
        self.X = X
        self.y = y
        self.macAddrDict = macAddrDict
        self.roomDict = roomDict

#定义分类器
class LocationClassifier(object):
	def __init__(self, classifier, wifiInfo, standardScaler):
	    self.classifier = classifier
	    self.wifiInfo = wifiInfo
	    self.standardScaler = standardScaler

	def convertWifiVector(self, wifiJsonString):
	    vector = np.zeros(len(self.wifiInfo.macAddrDict))
	    for mac in json.loads(wifiJsonString):
	        id = self.wifiInfo.macAddrDict[mac['mac']]
	        vector[id] = mac['level']
	    return vector

	def recognize(self,wifiJsonString):
	    vector = self.convertWifiVector(wifiJsonString)
	    vector [vector==0] = -100
	    vector = vector+100
	    vector = self.standardScaler.transform([vector])

	    #可能性
	    prob = self.classifier.predict_proba(vector)
	    prob_idx = np.argsort(-prob,axis=1)[0]
	    # 根据可能性排序，然后把这个arg_index套用到clf.classese_,以免出现跳跃式类别。这是范留山隐藏的坑
	    # 要按大一这样搞，算法这条路基本没希望了
	    pred_classes = self.classifier.classes_[prob_idx]

	    return pred_classes[:3], prob[0,prob_idx[:3]]#[0,pred_idx[:3]]  #返回前3个最大可能的类别，以及他们的可能性

	def return_as_json(self, wifiJsonString,wifiInfo):
		#'''直接返回json字串，该怎么输出自己再改''
		[c,p] = self.recognize(wifiJsonString)
		strData=""
		for i in range(len(c)):
			#tmpData['class'] = target_names[c[i]]
			strData+= str(wifiInfo.roomDict[c[i]])
			strData+=":"
			strData +=str(p[i])
			strData+=";"
		return strData

#加载
fp = open('locationClassifier.pkl','rb',True)
locationClassifier = pickle.load(fp,encoding='latin1')
fp = open('wifiInfo.pkl','rb',True)
wifiInfo = pickle.load(fp,encoding='latin1')

wifiJsonString='[{"mac":"0a:69:6c:76:fc:bb","level":"-45"},{"mac":"06:69:6c:76:fc:ba","level":"-47"},{"mac":"06:69:6c:76:fc:a6","level":"-51"},{"mac":"0a:69:6c:76:fc:a7","level":"-58"},{"mac":"0a:69:6c:77:09:cf","level":"-69"},{"mac":"06:69:6c:77:00:9e","level":"-72"}]'
print  (locationClassifier.return_as_json(wifiJsonString,wifiInfo))
