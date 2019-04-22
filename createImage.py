#get shopping list
#get layout
#get key

#convert layout to hex

#create locations

#draw grid while drawing line

#delete old image
#export image as svg


from random import randint
import random
from turtle import Turtle
import turtle
import csv
import os
import svgwrite
from svg_turtle import SvgTurtle
screen = turtle.Screen()
screen.tracer(0,0)


def importFromCsv():
    shoppingList = []
    for item in csv.reader(open('categoryNames.txt')):
        for item2 in item:
            shoppingList.append(item2)

    superMarketLayout = list(csv.reader(open('SuperMarketLayout.csv'))) #imports the layout of the supermarket as a list superMarketLayout[9-column][row] is the format for reading

    productKey = dict(csv.reader(open('productKey.csv'))) #imports the key which relates categories to hex values as a dictionary

    return shoppingList, superMarketLayout, productKey


def convertLayoutToHex(superMarketLayout,productKey):
    hexLayout = []
    for row in superMarketLayout:
        rowValues = []
        for item in row:
            rowValues.append(productKey[item])
        hexLayout.append(rowValues)
    return hexLayout


def convertListToHex(shoppingList,productKey):
    hexList = []
    for item in shoppingList:
        hexList.append(productKey[item])
    return hexList


def calculateDistance(location1,location2):
    def distanceBetweenCoords(coord1,coord2):
        square1 = coord1 ** 2
        square2 = coord2 ** 2
        added = square1 + square2
        output = added ** 0.5
        return output
    xDistance = abs(location1[0]-location2[0]) #difference in x
    yDistance = abs(location1[1]-location2[1]) #difference in y
    return distanceBetweenCoords(xDistance,yDistance)


def createLocations(hexLayout,hexList):
    lineLocations = []
    for row in range(len(hexLayout)): #This part determines the location of the door
        for position in range(len(hexLayout[row])):
            if hexLayout[9-position][row] == 'ff0000':
                doorLocation = (row,position)
    lineLocations.append(doorLocation) #The door is made to be the first place to go for the line
    
    locations = {'ff0000':[doorLocation]}
    for item in hexList:
        for row in range(len(hexLayout)):
            for position in range(len(hexLayout[row])):
                if hexLayout[9-position][row] == item:
                    if item in locations:
                        locations[item].append((row,position))
                    else:
                        locations[item] = [(row,position)]
                        
    while len(locations) > 0:
        closestKey = list(locations)[0]
        closestLoc = locations[closestKey][0]
        currentLocation = lineLocations[len(lineLocations)-1] #The most recently added location is the current one
        
        for listItem in locations:
            for potentialLocation in locations[listItem]: #For every location stored in locations. Every time any one of the items on the shopping list appear in the supermarket
                if calculateDistance(currentLocation,potentialLocation) < calculateDistance(currentLocation,closestLoc): #If the distance from the current loc to the potential loc is shorter than the value stored as being the current shortest
                    closestKey = listItem
                    closestLoc = potentialLocation
        locations.pop(closestKey)
        lineLocations.append(closestLoc)

    tillLocations = []
    tillDistances = []
    for row in range(len(hexLayout)): #This part determines the location of the tills, and their distances from the current line location
        for position in range(len(hexLayout[row])):
            if hexLayout[9-position][row] == '00ff00':
                tillLocations.append((row,position))
                tillDistances.append(calculateDistance(lineLocations[len(lineLocations)-1],(row,position)))

    closestTillLocation = tillLocations[tillDistances.index(min(tillDistances))]
    lineLocations.append(closestTillLocation)
    return lineLocations


def drawSquare(turt,width):
    turt.begin_fill()
    for side in range(4):
        turt.forward(width)
        turt.right(90)
    turt.end_fill()

def drawGrid(hexLayout,hexList,width,gridTurtle):
    gridTurtle.speed(0)
    gridTurtle.ht()

    for item in hexList:
        for row in range(len(hexLayout)):
            for position in range(len(hexLayout[row])):
                gridTurtle.penup()
                gridTurtle.goto(9-(position*width),(row*width))
                gridTurtle.pendown()
                gridTurtle.color(str(str("#")+str(hexLayout[9-row][9-position])))
                drawSquare(gridTurtle,width)

def drawLine(locations,width,lineTurtle):
    lineTurtle.color("#ff0000")
    lineTurtle.pensize(5)
    lineTurtle.speed(0)
    lineTurtle.ht()

    lineTurtle.penup()
    lineTurtle.goto(((locations[0][0]*width)-245,(locations[0][1]*width)-15))
    lineTurtle.pendown()
    for location in locations:
        lineTurtle.goto(((location[0]*width)-245,(location[1]*width)-15))
    

def mainProgram(t):
    shoppingList, superMarketLayout, productKey = importFromCsv()
    hexLayout = convertLayoutToHex(superMarketLayout,productKey)
    hexList = convertListToHex(shoppingList,productKey)
    locations = createLocations(hexLayout,hexList)

    width = 30
    drawGrid(hexLayout,hexList,width,t)
    drawLine(locations,width,t)


def createOutputImage(filename,size,screen):
    drawing = svgwrite.Drawing(filename, size=size)
    drawing.add(drawing.rect(fill='white', size=("50%", "50%")))
    t = SvgTurtle(drawing)
    mainProgram(t)
    screen.update()
    drawing.save()

os.remove("outputImage.svg")
createOutputImage('outputImage.svg',("550px", "550px"),screen)
