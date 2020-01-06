#!/usr/bin/env python3

import os
import sys
import json
import numpy as np
import cv2


class Image:
    def __init__(self, faces):
        self.faces = faces

class Face:
    def __init__(self, face, od, os):
        self.face = face
        self.od = od
        self.os = os


def detect_face(image, cascade="default"):
    coordinates = []
    # Cascade files
    face_file = f"../media/cascades/haarcascade_frontalface_{cascade}.xml"
    eye_file = "../media/cascades/haarcascade_eye.xml"
    face_cascade = cv2.CascadeClassifier(face_file)
    eye_cascade = cv2.CascadeClassifier(eye_file)

    # Convert image and detect faces
    img = cv2.imread(image)
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    faces = face_cascade.detectMultiScale(gray, 1.2, 5)

    # Eye detection
    for (x,y,w,h) in faces:
        count = 0
        scale = 1.0
        roi_gray = gray[y:y+h, x:x+w]

        # Re-scale image if we find more than two eyes
        while True:
            count += 1
            scale += 0.1
            eyes = eye_cascade.detectMultiScale(roi_gray, scale)
            if len(eyes) < 3:
                break
            if count > 20:
                break

        # Calculate eye positions and print results
        if len(eyes) == 2:
            if eyes[0][0] < eyes[1][0]:
                od = eyes[0]
                os = eyes[1]
            else:
                od = eyes[1]
                os = eyes[0]
        face = Face({'x':x, 'y':y, 'w':w, 'h':h},
                    {'x':od[0], 'y':od[1], 'w':od[2], 'h':od[3]},
                    {'x':os[0], 'y':os[1], 'w':os[2], 'h':os[3]})
        coordinates.append(face.__dict__)

    image = Image(coordinates)
    return image.__dict__

if __name__ == '__main__':
    # print(sys.argv[1])
    print(detect_face(sys.argv[1], sys.argv[2]))

