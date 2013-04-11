#!/usr/bin/python

from google.appengine.ext import endpoints
from protorpc import remote
from protorpc import message_types

from whisky_models import Whisky
from whisky_messages import WhiskyList
from whisky_messages import WhiskyRequest
from whisky_messages import WhiskyDetail

@endpoints.api(name='wweb', version='v1',
               description='An API for retrieving whiskies')
class WhiskyApi(remote.Service):
    @endpoints.method(message_types.VoidMessage, WhiskyList,
                      path='whisky', http_method='GET',
                      name='whisky.list')
    def whisky_list(self, request):
			"""Retrieve a list of whiskies in the system
			"""
			items = [entity.to_entry() for entity in whiskies]
			return WhiskyList(items=items)
    
    @endpoints.method(WhiskyRequest, WhiskyDetail,
                      path='whisky/{id}', http_method='GET',
                      name='whisky.get')
    def whisky_get(self, request):
			"""Query for a given whisky details
			"""
			return whiskies[request.id - 1].to_detail()

whiskies = [
	Whisky(1, "Hart Brothers 18", 
	"Scottish Highlands",
	"https://www.googleapis.com/freebase/v1/image/m/0291rn1?maxwidth=400",
	"Royal Brackla"),
	Whisky(2, "The Glenlivet 12", 
	"Strathspey",
	"https://www.googleapis.com/freebase/v1/image/m/02cjlm_?maxwidth=400",
	"The Glenlivet"),
	Whisky(3, "Bruchladdich 12 (2nd edition)", 
	"Islay",
	"http://upload.wikimedia.org/wikipedia/commons/thumb/6/62/Mysterious_Bruichladdich12.jpg/391px-Mysterious_Bruichladdich12.jpg",
	"Bruichladdich"),
	Whisky(4, "Bowmore Legend", 
	"Islay",
	"http://upload.wikimedia.org/wikipedia/commons/thumb/3/36/Bowmore_-_Legend.JPG/340px-Bowmore_-_Legend.JPG",
	"Bowmore")
];

APPLICATION = endpoints.api_server([WhiskyApi], restricted=False)
