#!/usr/bin/python

from protorpc import messages

class WhiskyDetail(messages.Message):
	"""ProtoRPC message definition to represent an individual whisky."""
	id = messages.IntegerField(1, required=True)
	title = messages.StringField(2, required=True)
	region = messages.StringField(3)
	image_url = messages.StringField(4)
	distillery = messages.StringField(5)

class WhiskyEntry(messages.Message):
	"""ProtoRPC message definition to represent an individual whisky."""
	id = messages.IntegerField(1, required=True)
	title = messages.StringField(2, required=True)

class WhiskyRequest(messages.Message):
	"""ProtoRPC message definition to represent a whisky query."""
	id = messages.IntegerField(1)

class WhiskyList(messages.Message):
	"""ProtoRPC message definition to represent a list of stored whiskies."""
	items = messages.MessageField(WhiskyEntry, 1, repeated=True)
