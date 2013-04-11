#!/usr/bin/python

from whisky_messages import WhiskyEntry
from whisky_messages import WhiskyDetail

class Whisky:	
	def __init__(self, id, title, region, image_url, distillery):
		self.id = id
		self.title = title
		self.region = region
		self.image_url = image_url
		self.distillery = distillery
		
	def to_entry(self):
		return WhiskyEntry(id=self.id, title=self.title)
		
	def to_detail(self):
		return WhiskyDetail(
				id=self.id,
				title=self.title,
				region=self.region,
				image_url=self.image_url,
				distillery=self.distillery)