## Smarter ##
Smarter is an open source real time webchat, where you can publicly chat with
the other users, or privately chat with your friends. Smarter also provides an
administration and a moderation system that provide a better control over your
website.
### Components ###
>	#### Backend ####
>		- NodeJS : ExpressJS
>		- JavaScript : JQuery, socket.io
> 		- MySQL
>		- Redis
>	#### Frontend ####
>		- HTML5
>		- CSS3
>		- Twitter Bootstrap
## Installation ##
```
git clone https://github.com/f1she3/smarter.git
```
- Install / Start [Redis](https://redis.io/) and listen on port `6379`
- Set the directory `www` as your webserver's root
- Import the database `smarter.sql`
- Edit `functions/init.php` according to your needs and your configuration
