<a name="readme-top"></a>

[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Docker][docker-shield]][docker-url]
[![Docker][docker-shield-old]][docker-url-old]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![MIT License][license-shield]][license-url]

<!-- ABOUT THE PROJECT -->
## About The Project

![Dashboard][screenshot-dashboard]
![DMARC-Reports][screenshot-dmarc]
![SMTP-TLS-Reports][screenshot-smtptls]

I needed a quick and easy way to view my DMARC and SMTP-TLS reports.
It also provides hosting for BIMI files and Outlook autodiscover.
This is a simple symfony project that can read the inbox for new reports and process them.
I you use it you should use it with a special sole-purpose email address.

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- LICENSE -->
## License

This project is licenced under the AGPLv3 with some additional terms.<br>
The additional terms are there to prevent rebranding and then pretending someone else made it.
<br>
See `LICENSE.txt` for both the license and the additional terms below.

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- GETTING STARTED -->
## Getting Started

### Prerequisites

* composer
* mariadb (10.5+)/postgresql (13+)/sqlite
* php (8.2+)
* php-ctype
* php-curl
* php-dom
* php-fileinfo
* php-fpm
* php-iconv
* php-imap
* php-mbstring
* php-pdo
* php-pdo-mysql
* php-pdo-pgsql
* php-pdo-sqlite
* php-phar
* php-session
* php-simplexml
* php-tokenizer
* php-xml
* php-xmlwriter
* php-zip
* php-zlib (*)

\* php-zlib is enabled on redhat based systems by default and not availible as a package


### Installation

1. Clone the repo
   ```sh
   git clone https://github.com/antedebaas/Viesti-Reports.git
   ```
2. run update.sh to update project, clear its cache, this prevents symfony caching issues
   ```sh
   bash [root path of this project]/update.sh
   ```
3. point the webserver root to the public/ directory
4. point your webbrowser to http(s)://[yourhost]/setup and follow instructions
   
   a. after the first page (which generates `.env.local`) if you are **not using sqlite** you need to run
   ```sh
   php [root path of this project]/bin/console cache:clear
   ```
5. run installservice.sh to install the systemd service and timer for automated mail checking
   ```sh
   bash [root path of this project]/installservice.sh
   ```
6. if you put it on https://mta-sts.yourmdomain.ext it will provide an mta-sts policy file (https://mta-sts.yourmdomain.ext/.well-known/mta-sts.txt)
   you can edit the policy on the domain edit page.
   It will also host https://autoconfig.yourmdomain.ext and https://autodiscover.yourmdomain.ext outlook autoconfiguration files
   And it supports BIMI file hosting on https://bimi.yourmdomain.ext


<p align="right">(<a href="#readme-top">back to top</a>)</p>

### Usage notes

Sometimes the check mail process gets locked and it will say so in the logs.
to unlock it run `php bin/console app:removemaillock`

<p align="right">(<a href="#readme-top">back to top</a>)</p>

### Docker

1. see the docker-compose.yml file for all variables and an example stack.
2. `MAILCHECK_SCHEDULE` can be adjusted to check the mailbox more or less frequent using a cron syntax.
   you can also use one of the following keywords: monthly, weekly, daily, hourly, 15min
3. run `docker compose up`

<p align="right">(<a href="#readme-top">back to top</a>)</p>

## Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, please fork the repo and create a pull request. You can also simply open an issue with the tag "enhancement".
Don't forget to give the project a star! Thanks again!

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- TRANSLATING -->
## Translating

![Lokalise][logo-lokalise]<br>
<a href="http://www.lokalise.com/">Lokalise.com</a> has provided us with an opensource licence for translations.

If you want to contribute you can join the project there using the following link:<br>
https://app.lokalise.com/public/943233106735f8aa5bd752.59947412/

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- MARKDOWN LINKS & IMAGES -->
[contributors-shield]: https://img.shields.io/github/contributors/antedebaas/Viesti-Reports.svg?style=for-the-badge
[contributors-url]: https://github.com/antedebaas/Viesti-Reports/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/antedebaas/Viesti-Reports.svg?style=for-the-badge
[forks-url]: https://github.com/antedebaas/Viesti-Reports/network/members
[stars-shield]: https://img.shields.io/github/stars/antedebaas/Viesti-Reports.svg?style=for-the-badge
[stars-url]: https://github.com/antedebaas/Viesti-Reports/stargazers
[issues-shield]: https://img.shields.io/github/issues/antedebaas/Viesti-Reports.svg?style=for-the-badge
[issues-url]: https://github.com/antedebaas/Viesti-Reports/issues
[license-shield]: https://img.shields.io/github/license/antedebaas/Viesti-Reports.svg?style=for-the-badge
[license-url]: https://github.com/antedebaas/Viesti-Reports/blob/master/LICENSE.txt
[docker-shield]: https://img.shields.io/docker/pulls/antedebaas/viesti-reports.svg?style=for-the-badge
[docker-url]: https://hub.docker.com/repository/docker/antedebaas/viesti-reports/general
[docker-shield-old]: https://img.shields.io/docker/pulls/antedebaas/dmarc-reports.svg?style=for-the-badge
[docker-url-old]: https://hub.docker.com/repository/docker/antedebaas/dmarc-reports/general

[screenshot-dashboard]: screenshot-dashboard.png
[screenshot-dmarc]: screenshot-dmarc.png
[screenshot-smtptls]: screenshot-smtptls.png

[logo-lokalise]: logo-lokalise.svg
