<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## I chose to use the Laravel Cashier package for Stripe integration in my project. Here's why:

In my opinion, this package is most suitable for our task. Laravel Cashier is a package specifically designed for 
Laravel applications to simplify subscription billing with Stripe. It provides a higher-level abstraction that 
integrates seamlessly with Laravel's ecosystem.
I chose Laravel Cashier for its seamless integration with Laravel, which simplified our payment processing implementation.
With Laravel Cashier, we could easily manage subscriptions, handle payment methods, and automate tasks like invoicing
and dunning. This package not only streamlines the Stripe integration but also aligns perfectly with Laravel's
developer-friendly ecosystem, making it a natural choice for our project.

## Stripe Configuration

In order to integrate Stripe with this project, follow these steps to set up your Stripe API keys and webhook secret key
(for protect incoming requests with Cashier's included webhook signature verification middleware) in the `.env` file: 
To listen to Stripe events, you will need a secure webhook URL with https. If you are on a local server with http then 
you can use ngrok or any similar tool for this and then in your account dashboard on this page 
https://dashboard.stripe.com/test/webhooks/create you can create Webhook URL or you can do it from your project folder
running this command replacing the secure webhook URL and add /stripe/webhook

    php artisan cashier:webhook --url "https://your-ngrok-url.com/stripe/webhook"

1. Open the `.env` file in the root directory of your project.

2. Locate the following lines and replace them with your actual Stripe API keys and webhook secret key:

   `.env`
   STRIPE_KEY=your_publishable_key
   STRIPE_SECRET=your_secret_key
   STRIPE_WEBHOOK_SECRET=your-stripe-webhook-secret

3. Save the `.env` file.

###### Now, your Laravel project is configured to use your Stripe account for payment processing and subscriptions.

**Note:** Ensure that you keep your `.env` file secure and do not share it publicly, as it contains sensitive information.


## You will need to create two products in your Stripe account, then open .env and replace those values before seeding.
   
  `.env`
  STRIPE_PRODUCT_1=your_product_1_price_api_id
  STRIPE_PRODUCT_2=your_product_2_price_api_id

## Email Configuration - Update the .env file with your email credentials.

     MAIL_MAILER=smtp
     MAIL_HOST=mailpit
     MAIL_PORT=1025
     MAIL_USERNAME=null
     MAIL_PASSWORD=null
     MAIL_ENCRYPTION=null
     MAIL_FROM_ADDRESS="hello@example.com"
     MAIL_FROM_NAME="${APP_NAME}"

