# MIRЯOR (Minimum Viable Product)
_Reflect your Style_

We are a luxury e-commerce website, selling a wide range of clothing in different categories. If you care about the fashion you wear, and the way you present yourself, we are here for you.
This code is the MVP (Minimum Viable Product) version of the website, meaning only the bare-bones functionality is available.

**The website is being hosted at https://220134662.cs2410-web01pvm.aston.ac.uk/**

## The Team
MIRЯOR is being developed by Team 18 for the Team Project module at Aston University. Team 18 consists of the following people (please see other documentation for Student IDs):
- Harisullah Jan ([@harisullah10](https://github.com/harisullah10 "harisullah10"))
- Jack Jones ([@JackJones04](https://github.com/JackJones04 "JackJones04"))
- Harleen Kaur ([@HarleenKaur3](https://github.com/HarleenKaur3 "HarleenKaur3 (Harleen Kaur)"))
- Pawel Kedzia ([@PearOrchards](https://github.com/PearOrchards "PearOrchards (Pawel)"), commits as "Pawel" or "Pear")
- Rachel Agyapong ([@Rachelagya](https://github.com/Rachelagya "Rachelagya"), commits as "Rachel")
- Durga Mehmi ([@DurgaMe](https://github.com/DurgaMe "DurgaMe"))
- Muhammad Khalid ([@inshall123](https://github.com/inshall123 "inshall123"))
> "commits as" comments have been added where GitHub may not have correctly linked commits with accounts.

## Current Functionality
The full list of functionality (and who did it) is as follows:
- See the home page (Pawel) and about us (Inshall) pages
- Send us feedback (Inshall)
- Look at and leave us a review (Rachel)
- Register for, and log into your account (Durga)
- Manage your account, and look at previous orders (Jack)
- Discover our products (Pawel), navigate to its page (Haris) and add it to your basket (Harleen)
- Checkout (Harleen)

## Running Locally
If you would like to run the website on your machine, please follow the following instructions:
1. Get XAMPP from [here](https://www.apachefriends.org/ "Apache Friends").
2. You will also need Composer if you don't already have it, get it from [here](https://getcomposer.org/download/ "Get Composer").
3. Clone this repo inside the `htdocs` folder in XAMPP, by default this would be `C:/xampp/htdocs` (assuming you're on Windows)
4. Run the command `composer install` inside of the cloned folder to install the dotenv package.
5. Create a `.env` file in the folder, fill it out with the details of your database.
6. Start Apache and MySQL from the XAMPP Control Panel.
7. Open phpMyAdmin by clicking on the `Admin` button next to MySQL.
8. Create a database (we called ours `mvp`, but it is up to you!), and then import the database file that's in this repo, [this one](init_table.sql "init_table.sql").
9. You can now navigate to `localhost/<folder name>`, and the website will be up and running!

_Do not forget to import the database and create the .env file!_