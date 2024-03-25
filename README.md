# MIRЯOR (Final)
_Reflect your Style_

We are a luxury e-commerce website, selling a wide range of clothing in different categories. If you care about the fashion you wear, and the way you present yourself, we are here for you.
This is the repository for the final version of the website, meaning we are aiming to finish all the requirements for the project, and have a fully functional website before the final deadline, at the **25th of March, 2024**.

**The website (with the current main branch) is being hosted at https://220134662.cs2410-web01pvm.aston.ac.uk/**

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
1. Get Laragon from [here](https://laragon.org/download/ "Laragon"). We would recommend downloading the "Full" edition.
2. If you do not have Composer already, download it from [here](https://getcomposer.org/download/ "Get Composer").
3. Clone this repo inside the `www` folder in Laragon, by default this would be `C:/laragon/www` (assuming you're on Windows)
4. Run the command `composer install` inside of the cloned folder to install the dotenv package.
5. Create a `.env` file in the folder, fill it out with the details of your database.
6. Open Laragon **as admin**. This will allow it to create a virtual host for the website.
7. By default, HeidiSQL is the default database manager for Laragon. If you would like to use phpMyAdmin, you can do the following:
   - Right click anywhere on the Laragon window
   - In the context menu, go to Tools > Quick Add > *phpMyAdmin
   - It will download phpMyAdmin for you, and open it automatically.
8. Open your database manager by clicking on the `Database` button at the bottom.
9. Simply create a database (we called ours `mirror`, but it is up to you!). The tables will be created automatically.
10. You can now navigate to `<folder_name>.test`, and the website will be up and running!
11. After the first time, you will not need to run Laragon as admin again.

_Do not forget to create the database and create the .env file!_

Note: A previous version of these steps used XAMPP. By all means, it still works, but we have found Laragon to be more user-friendly and easier to set up, and the power brought by the virtual host system is astonishing.