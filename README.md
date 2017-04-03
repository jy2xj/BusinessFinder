# BusinessFinder
Business Finder is a Web Application that helps a user pick a restaurant or an experience. Using Yelp's Fusion API, Business Finder allows you to search up a term, answer a few questions, and get three recommended results from your answers. There is also a trending page that gives recommendations for cuisines and places to search, and an about page that talks more about why the website was created and what the website hopes to accomplish.  

The web application uses HTML, CSS, and JS on the client-side, and PHP on the server side. On the index page, users are prompted to share their location, enter a search term, and answer a few questions, which is stored in a JS dictionary. The dictionary is then sent in an AJAX POST request to the php script, which returns a JSON string that is then parsed to show the businesses and locations on a map.

Main Feature:
- Shows three restaurants/experiences to choose from and lists out information about the business (Name, Yelp link, Image, Address, Phone number)

Extra Features:
- Has a page for trending cuisines and places in America
- Obtains and uses the user's location via HTML5 Geolocation
- Plots merchants (and your location) on a map using the Google Maps API
- Interactive user interface with question answering

### Installing

In order to get this working on your server, you need a few things:
- A Heroku account
- PHP installed locally
- Composer installed locally

Once you have all of that done, you need to clone the BusinessFinder repository onto your computer:

```
git clone https://github.com/jy2xj/BusinessFinder.git
```

From this point, we're almost done! All that's left is to deploy on the Heroku server.
Make sure to do all of this in the root directory of the BusinessFinder repository:

```
heroku login
heroku create
git push heroku master
```

And to start up an instance and open the website on your browser:
```
heroku ps:scale web=1
heroku open
```

## Authors

* **Joshua Ya** - *Creation of Website* - [Github Link](https://github.com/jy2xj)
