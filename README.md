# BusinessFinder
Business Finder is a Web Application that helps a user pick a restaurant or an experience with a focus on having a clean and simple design. Using Yelp's Fusion API, Business Finder allows you to search up a term, answer a few questions, and get three recommended results from your answers. There is also a trending page that gives recommendations for cuisines and places to search, and an about page that talks more about why the website was created and what the website hopes to accomplish.  

The web application uses HTML, CSS, and JS on the client-side, and PHP on the server side. On the index page, users are prompted to share their location, enter a search term, and answer a few questions, which is stored in a JS dictionary. The dictionary is then sent in an AJAX POST request to the php script, which returns a JSON string that is then parsed to show the businesses and locations on a map.

Main Feature:
- Shows three restaurants/experiences to choose from and lists out information about the business (Name, Yelp link, Image, Address, Phone number)

Extra Features:
- Has a page for trending cuisines and places in America
- Obtains and uses the user's location via HTML5 Geolocation
- Plots merchants (and your location) on a map using the Google Maps API
- Interactive user interface with question answering

### Installing and Running the App

-1- In order to get this working on a Heroku server, you need a few things. If you are just trying to get working on localhost, go to -2-:
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

In addition, to connect to Yelp's Fusion API, an ID and secret is required, 
which can be found at this link: https://www.yelp.com/developers
You should make changes to lines 20 and 21 in questions.php:

```
$CLIENT_ID = getenv('ID');
$CLIENT_SECRET = getenv('SECRET');
```

You can also just add config vars ID and SECRET to Heroku:

```
heroku config:set ID=YELP_FUSION_ID
heroku config:set SECRET=YELP_FUSION_SECRET
```

Also, make sure you change the JS key from the Google Maps API to your own in index.html:

```
<script src="https://maps.googleapis.com/maps/api/js?key=YOURGOOGLEAPIKEY"></script>
```

And to start up an instance and open the website on your browser:
```
heroku ps:scale web=1
heroku open
```

-2- If you are trying to get this working on your local server, you only need four files in the web directory: index.html, trending.html, about.html, and questions.php. You will have to change some paths in the files as they are locally pathed for Heroku. In addition, there are a few things you have to do.

To connect to Yelp's Fusion API, an ID and secret is required, 
which can be found at this link: https://www.yelp.com/developers
You should make changes to lines 20 and 21 in questions.php:

```
$CLIENT_ID = getenv('ID');
$CLIENT_SECRET = getenv('SECRET');
```

Also, make sure you change the JS key from the Google Maps API to your own in index.html:

```
<script src="https://maps.googleapis.com/maps/api/js?key=YOURGOOGLEAPIKEY"></script>
```

## Authors

* **Joshua Ya** - *Creation of Website* - [Github Link](https://github.com/jy2xj)
