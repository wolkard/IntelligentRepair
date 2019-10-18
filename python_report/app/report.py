#-*- coding: UTF-8 -*-
from app import app
from flask import request

from sklearn.preprocessing import StandardScaler
from sklearn.svm import SVC

import keras 
import numpy as np 
from keras.models import model_from_yaml 
from PIL import Image
from PIL import ImageOps 
import os
import json  

import pickle

@app.route('/image',methods=["GET"])#识别图片
def image():
	class ImgRecognition(object):   
		u'''识别一个或者多个图片的类ImgRecognition    
		def __init__(self, modelString, weights, target_names):# 初始化，参数：keras模型的描 述string，权重h5，类别对应的字符名list    
		'''   
		img_width, img_height = 256, 256
		def __init__(self, model_path, weights_path, target_names):# 初始化，参数：keras模 型的描述string，权重h5，类别对应的字符名list        
			self.target_names = target_names               
			with open(model_path, 'r') as myfile:            
				yaml_string=myfile.read()        
			self.model = keras.models.model_from_yaml(yaml_string)        
			self.model.load_weights(weights_path)            
		def recognize(self, pic_file):        
			'''返回前三个可能的类别和可能性'''        
			img = Image.open(pic_file).resize([img_recognition.img_width,img_recognition .img_height])        
			pic_matrix = np.asarray(img)/255.0     #这里很重要，要除以255.0，正规化一下        
			pred_y = self.model.predict(np.expand_dims(pic_matrix, axis=0))        
			pred_idx = np.argsort(pred_y,axis=1)[0]        
			pred_idx = pred_idx[::-1]        
			return  pred_idx[:3], pred_y[0,pred_idx[:3]]  #返回前3个最大可能的类别，以及他们的 可能性        
		def return_as_json(self, pic_file):        
			'''直接返回json字串，该怎么输出自己再改'''        
			[c,p] = self.recognize(pic_file)       
			strData='' #最终返回的数据   
			for i in range(len(c)):            
				strData+=str(target_names[c[i]].encode('raw_unicode_escape').decode())+","+str(p[i])+";"
			return (strData.encode("utf-8"))
	#target_names = os.listdir(rootPath) 
	target_names = ['101_\xe7\x8b\xac\xe7\xab\x8b\xe5\x87\xb3\xe5\xad\x90', '102_\xe9\x98\xb3\xe5\x8f\xb0\xe9\x97\xa8', '103_\xe6\x9f\x9c\xe5\xad\x90', '104_\xe5\xba\x8a', '105_\xe5\xae\xbf\xe8\x88\x8d\xe6\x9c\xa8\xe9\x97\xa8', '106_\xe6\x9a\x96\xe6\xb0\x94\xe7\x89\x87', '107_\xe4\xbe\x9b\xe6\x9a\x96\xe7\xae\xa1\xe9\x81\x93', '108_\xe6\x8f\x92\xe5\xba\xa7', '109_\xe8\xae\xb2\xe6\xa1\x8c', '10_\xe6\x8f\x92\xe6\x8e\x92', '110_\xe5\xa4\x9a\xe5\xaa\x92\xe4\xbd\x93', '111_\xe5\xbc\x80\xe5\x85\xb3', '112_\xe6\xa5\xbc\xe9\x81\x93\xe9\x97\xa8', '113_\xe6\xa5\xbc\xe6\xa2\xaf', '114_\xe5\x8e\x95\xe6\x89\x80\xe5\xa4\xa7\xe9\x97\xa8', '11_\xe7\x94\xb5\xe7\xba\xbf', '121_\xe4\xbe\xbf\xe5\x9d\x91', '122_\xe4\xbe\xbf\xe6\xb1\xa0', '123_\xe6\xb0\xb4\xe7\xae\xa1', '124_\xe6\xb0\xb4\xe6\xb1\xa0', '125_\xe5\xb0\x8f\xe5\x8e\x95\xe6\x89\x80\xe9\x97\xa8' , '126_\xe6\xb0\xb4\xe9\xbe\x99\xe5\xa4\xb4', '141_\xe9\xa3\x8e\xe6\x89\x87', '142_\xe6\x8a\x95\xe5\xbd\xb1\xe4\xbb\xaa', '143_\xe9\x97\xa8', '145_\xe7\x94\xb5\xe7\x81\xaf', '147_\xe6\xa1\x8c\xe6\xa4\x85', '14_\xe6\x9a\x96\xe6\xb0\x94\xe7\x89\x87', '15_\xe6\x9a\x96\xe6\xb0\x94\xe7\xae\xa1\xe9\x81\x93', '16_\xe6\x9a\x96\xe6\xb0\x94\xe4\xba\x95\xe7\x9b\x96', '1_\xe6\xb0\xb4\xe6\xb1\xa0', '2_\xe9\xa5\xae\xe6\xb0\xb4\xe6\x9c\xba', '3_\xe6\xb6\x88\xe9\x98\xb2\xe6\xa0\x93', '6_\xe6\xb0\xb4\xe9\xbe\x99\xe5\xa4\xb4', '8_\xe6\xb0\xb4\xe7\xae\xa1', '9_\xe9\xa3\x8e\xe6\x89\x87'] 
	#初始化识别类 
	img_recognition = ImgRecognition('app/model.txt','app/weights.h5',target_names)
	#识别
	result = img_recognition.return_as_json('../../lampp/htdocs/repair/public/recognitionImages.jpg')
	return result
@app.route('/location',methods=["POST"])#识别位置
def location():
	#接收php发送的json数据
	wifiJsonString = request.json['mac']
	#加载
	fp =open('app/location/wifiInfo.pkl','rb')
	wifiInfo = pickle.load(fp,encoding='latin1')
	fp =open('app/location/locationClassifier.pkl','rb')
	locationClassifier = pickle.load(fp,encoding='latin1')
	return  locationClassifier.return_as_json(wifiJsonString,wifiInfo)
