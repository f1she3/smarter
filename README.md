## Smarter ##

Smarter is an open source real time webchat, where you can publicly chat with
the other users, or privately chat with your friends. Smarter also provides an
administration and a moderation system that provide a better control over your
website.

![Chat page](https://raw.githubusercontent.com/f1she3/smarter/master/screenshots/chat.png?raw=true "Smarter")

![Login page](https://raw.githubusercontent.com/f1she3/smarter/master/screenshots/login.png?raw=true "Login")

### Components ###
>	#### Backend ####
>		- PHP
>		- JavaScript + JQuery
> 		- MySQL
>	#### Frontend ####
>		- HTML5
>		- CSS3
>		- Twitter Bootstrap
## Installation ##
```
git clone https://github.com/f1she3/smarter.git
```
- Install & start [Nginx](https://nginx.org/) and [MySQL](https://mariadb.org/): 
  ### Debian ###
  `sudo apt install nginx mysql-server`
  ### Arch Linux ###
  `sudo pacman -S nginx mariadb`

- Copy nginx.conf to your config file
- Import the database `smarter.sql`
- Edit `functions/init.php` according to your needs and your configuration
