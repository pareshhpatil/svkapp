<?php

require_once UTIL . 'SEOMeta.php';
require_once UTIL . 'OpenGraph.php';
require_once UTIL . 'JsonLd.php';

class Swipezweb extends Controller {

    private $OpenGraph = null;

    function __construct() {
        parent::__construct();
        $this->OpenGraph = new OpenGraph();
        $this->smarty->assign("canonical", $_SERVER['REQUEST_URI']);
        $this->smarty->assign("env", $this->env);
        $this->smarty->assign("loggedin", $this->session->get('logged_in'));
    }

    function home() {
        try {
            SEOMeta::setTitle('Swipez | Products for online payment collections');
            SEOMeta::setDescription('Swipez provides easy to use online payment collection solutions for businesses. Organize your day to day business operations and collect payments online faster.');
            SEOMeta::addKeyword(['collect payments online', 'automate business operations', 'billing software', 'event registration & online ticketing', 'booking calendar', 'website builder', 'url shortener']);
            SEOMeta::addMeta('application-name', $value = 'Swipez', $name = 'name');
            SEOMeta::addMeta('name', $value = 'Swipez', $name = 'itemprop');
            SEOMeta::addMeta('description', $value = 'Products for online payment collections', $name = 'itemprop');
            $this->OpenGraph->setTitle('Swipez | Products for online payment collections.');
            $this->OpenGraph->setDescription('Swipez provides easy to use online payment collection solutions for businesses. Organize your day to day business operations and collect payments online faster.');
            $this->OpenGraph->setUrl('https://www.swipez.in');
            $meta = SEOMeta::generate();

            $this->smarty->assign("meta", $meta . $og);
            $this->smarty->display(VIEW . 'swipezweb/master.tpl');
            $this->smarty->display(VIEW . 'swipezweb/index.tpl');
            $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E060]Error while home page initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Returns view for billing landing page
     *
     * @return view
     */
    public function billing() {
        SEOMeta::setTitle('Online billing software and invoicing system for your business.');
        SEOMeta::setDescription('Billing software for businesses of all sizes. Send GST ready invoices online, set payment reminders and collect payments online.');
        SEOMeta::addKeyword(['online invoicing software', 'billing software', 'online invoice', 'billing software', 'collect payments online', 'automate business operations']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Billing software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Billing software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Online billing software & invoicing system for your business.', $name = 'itemprop');

        $this->OpenGraph->setTitle('Online billing software & invoicing system for your business.');
        $this->OpenGraph->setDescription('Billing software for businesses of all sizes. Send GST ready invoices online, set payment reminders and collect payments online.');
        $this->OpenGraph->setUrl('https://www.swipez.in/billing-software');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();

        JsonLd::setTitle('Billing Software Solutions');
        JsonLd::setDescription('Send invoices online, set automatic follow-up reminders, and balance your books. Our billing software helps you collect faster via online payment modes like UPI, Wallets, Credit, Debit Card or Net Banking – so you can make way for growth.');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Jayesh Shah","datePublished":"2018-04-01","description":"Now I can easily send & reconcile our companies telephone and internet bills with multiple payment options to thousands of our customers via e-mail and SMS at the click of a button through Swipez.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Vikas Sankhla","datePublished":"2018-03-25","description":"We are now managing payments across our complete customer base along with timely pay outs for all franchisees across the country from one dashboard. My team has saved over hundred hours after adopting Swipez Billing.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software.svg');
        $jsonld = JsonLd::generate();
        $this->smarty->assign("meta", $meta . $og . $jsonld);

        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/product/billing.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for events landing page
     *
     * @return view
     */
    public function event() {
        SEOMeta::setTitle('Online event registration and ticketing platform');
        SEOMeta::setDescription('Online event management software for organizers to create events, collect online payments, manage box office, analyze events & attendees for their events.');
        SEOMeta::addKeyword(['event ticketing platform', 'event management platform', 'event management software', 'event planning software', 'event registration software', 'event management']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Event registration software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Event registration software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Online event registration and ticketing platform', $name = 'itemprop');

        $this->OpenGraph->setTitle('Online event registration and ticketing platform');
        $this->OpenGraph->setDescription('Online event management software for organizers to create events, collect online payments, manage box office, analyze events & attendees for their events.');
        $this->OpenGraph->setUrl('https://www.swipez.in/event-registration');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();

        JsonLd::setTitle('Event Ticketing Solutions');
        JsonLd::setDescription('Create events, manage ticket sales, promote, and collate customer information for future events and more.');
        JsonLd::setImage('https://www.swipez.in/images/product/online-event-registration.svg');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Avinash Agarwal","datePublished":"2018-04-01","description":"We have hosted multiple events with no issues over last couple of years. The support team at Swipez have been great in responding quickly and solving our queries effectively.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Aniruddha Patil","datePublished":"2018-03-25","description":"From creating events to collecting ticket costs online it has been a breeze. The flexibility provided by the event creation tool is perfect, it has been able to meet our every need.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        $jsonld = JsonLd::generate();
        $this->smarty->assign("meta", $meta . $og . $jsonld);

        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/product/event.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for booking calendar landing page
     *
     * @return view
     */
    public function venuebooking() {
        SEOMeta::setTitle('Venue booking software to manage timeslot bookings');
        SEOMeta::setDescription('Manage times slots for your venues, collect payments online and publish your venue calendar link. Ideal for courts, studios, rooms and spaces at your venue.');
        SEOMeta::addKeyword(['venue management software', 'venue scheduling software', 'venue booking software', 'facility management software', 'facility scheduling software', 'facility booking software', 'room scheduling software', 'resource scheduling software', 'time slot booking software']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Venue booking software', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Venue booking software', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Venue booking software to manage timeslot bookings', $name = 'itemprop');

        $this->OpenGraph->setTitle('Venue booking software to manage timeslot bookings');
        $this->OpenGraph->setDescription('Manage times slots for your venues, collect payments online and publish your venue calendar link. Ideal for courts, studios, rooms and spaces at your venue.');
        $this->OpenGraph->setUrl('https://www.swipez.in/venue-booking-software');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();

        JsonLd::setTitle('Venue Booking Software');
        JsonLd::setDescription('Manage time slots across one or many venues, schedule appointments and publish your booking calendar to your customers.');
        JsonLd::setImage('https://www.swipez.in/images/product/venue-booking-software.svg');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Mahesh Shelote","datePublished":"2018-04-01","description":"Earlier time slot bookings for all our facilities & venues was via phone calls and tracked manually on a register. Now with the booking calendar our residents book a facility online within minutes.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}},{"@type":"Review","author":"Lokesh Sonawane","datePublished":"2018-03-25","description":"Time slot bookings for our badminton courts is now completely online. Our players are now able to view availability, book a time slot, schedule a coaching session and pay us online via their mobile phones","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        $jsonld = JsonLd::generate();
        $this->smarty->assign("meta", $meta . $og . $jsonld);

        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/product/venuebooking.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for website builder landing page
     *
     * @return view
     */
    public function websitebuilder() {
        SEOMeta::setTitle('Create free business website with online payments in minutes');
        SEOMeta::setDescription('Build your free online payment enabled business website. Create a stunning, easily customizable site in minutes. Publish on your own domain with out any coding.');
        SEOMeta::addKeyword(['website with online payments', 'website builder online', 'website maker', 'online website builder', 'website builder online']);
        SEOMeta::addMeta('application-name', $value = 'Swipez Website builder', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez Website builder', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Create free business website with online payments in minutes', $name = 'itemprop');

        $this->OpenGraph->setTitle('Create free business website with online payments in minutes');
        $this->OpenGraph->setDescription('Build your free online payment enabled business website. Create a stunning, easily customizable site in minutes. Publish on your own domain with out any coding.');
        $this->OpenGraph->setUrl('https://www.swipez.in/website-builder');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();

        JsonLd::setTitle('Website builder');
        JsonLd::setDescription('Build your company website with online payments in minutes. Build your business presence online with website builder and gain brand recognition.');
        JsonLd::setImage('https://www.swipez.in/images/product/online-website-builder.svg');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Harish Boardake","datePublished":"2018-04-01","description":"We were able to create our company website within a day. Our customers now pay online and renew their internet packages from our company website.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Victor Cardoz","datePublished":"2018-03-25","description":"It was easy to create a professional website for our consultancy services. Our website has helped us attract clients via online search.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        $jsonld = JsonLd::generate();
        $this->smarty->assign("meta", $meta . $og . $jsonld);

        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/product/website.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for url shortener landing page
     *
     * @return void
     */
    public function urlshortener() {
        SEOMeta::setTitle('URL shortener for enterprise');
        SEOMeta::setDescription('URL Shortener with custom domains. Create, track and optimize your campaign with branded links. Enteprise link management platform with custom API integrations.');
        SEOMeta::addKeyword(['shorten long urls', 'url shortener', 'URL redirect', 'branded tiny url', 'branded short url']);
        SEOMeta::addMeta('application-name', $value = 'Swipez URL shortener', $name = 'name');
        SEOMeta::addMeta('name', $value = 'Swipez URL shortener', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'URL shortener for enterprise', $name = 'itemprop');

        $this->OpenGraph->setTitle('URL shortener for enterprise');
        $this->OpenGraph->setDescription('URL Shortener with custom domains. Create, track and optimize your campaign with branded links. Enteprise link management platform with custom API integrations.');
        $this->OpenGraph->setUrl('https://www.swipez.in/url-shortener');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();

        JsonLd::setTitle('Url Shortener');
        JsonLd::setDescription('Shorten long URLs instantaneously. Track, manage and free up characters in your customer communications - unleash the power of your links.');
        JsonLd::setImage('https://www.swipez.in/images/product/url-shortener.svg');
        JsonLd::addValue('review', json_decode('[{"@type":"Review","author":"Chandra Gupta","datePublished":"2018-04-01","description":"We have seamlessly added URL shortening capability to our technology stack. Now for all our client communications we are able to create and track shortened links the via the Swipez URL shortener.","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"1","worstRating":"1"}},{"@type":"Review","author":"Viren Hundekari","datePublished":"2018-03-25","description":"All our internet and telcom bills are now sent to our customers as a short link via SMS. This has reduced late payments by our customers significantly. It is now simple to send new offers and plans to our customers as well","name":"Value purchase","reviewRating":{"@type":"Rating","bestRating":"5","ratingValue":"4","worstRating":"1"}}]', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        $jsonld = JsonLd::generate();
        $this->smarty->assign("meta", $meta . $og . $jsonld);

        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/product/urlshortener.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for cable industry landing page
     *
     * @return void
     */
    public function cable() {
        SEOMeta::setTitle('Cable operator billing software with online payments');
        SEOMeta::setDescription('Online billing software for cable operators with subscriber management, online payments and package or channel selection for customers.');
        SEOMeta::addKeyword(['cable billing software', 'online invoicing software', 'online billing system', 'online invoice', 'subscriber management', 'billing software', 'invoicing with online payments', 'billing with online payments']);
        SEOMeta::addMeta('name', $value = 'Billing software for cable  operators', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Cable operator billing solution with subscriber management and online payments', $name = 'itemprop');
        $this->OpenGraph->setTitle('Cable operator billing software with online payments');
        $this->OpenGraph->setDescription('Online billing software for cable operators with subscriber management, online payments  and package or channel selection for customers.');
        $this->OpenGraph->setUrl('https://www.swipez.in/billing-software-for-cable-operator');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();
        JsonLd::setTitle('Billing software for cable operators');
        JsonLd::setDescription('Send invoices online, set automatic follow-up reminders, and balance your books. Our billing software helps you collect faster via online payment modes.');
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/industry/cable-operator.svg');
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"89"}', 1));
        $jsonld = JsonLd::generate();
        $this->smarty->assign("meta", $meta . $og . $jsonld);
        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/industry/cable.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for franchise industry landing page
     *
     * @return void
     */
    public function franchise() {
        SEOMeta::setTitle('Billing software for franchise based business');
        SEOMeta::setDescription('Online billing software for franchise business with invoicing, payment reminders, GST filing and payouts.');
        SEOMeta::addKeyword(['franchise billing software', 'online invoicing software', 'online billing system', 'online invoice', 'GST filing', 'billing software', 'invoicing with online payments', 'billing with online payments']);
        SEOMeta::addMeta('name', $value = 'Billing software for franchise based business', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Billing solution for franchise businesses of all sizes', $name = 'itemprop');

        $this->OpenGraph->setTitle('Billing software for franchise based business');
        $this->OpenGraph->setDescription('Online billing software for franchise business with invoicing, payment reminders, GST filing and payouts.');
        $this->OpenGraph->setUrl('https://www.swipez.in/billing-software-for-franchise-business');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();

        JsonLd::setTitle('Billing software for franchise business');
        JsonLd::setDescription('Strengthening franchisee networks by virtue of streamlining business operations using invoicing software.');
        JsonLd::setImage('https://www.swipez.in/assets/swipez-web/images/product/billing-software/industry/franchise-business.svg');
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.5","reviewCount":"95"}', 1));
        $jsonld = JsonLd::generate();
        $this->smarty->assign("meta", $meta . $og . $jsonld);
        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/industry/franchise.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for isp industry landing page
     *
     * @return void
     */
    public function isp() {
        SEOMeta::setTitle('Billing software for internet service providers');
        SEOMeta::setDescription('Online billing software for internet service providers with subscriber management, online payments and package selection for customers.');
        SEOMeta::addKeyword(['isp billing software', 'online invoicing software', 'online billing system', 'online invoice', 'subscriber management', 'billing software', 'invoicing with online payments', 'billing with online payments']);
        SEOMeta::addMeta('name', $value = 'Billing software for ISP', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Billing solution for ISP with custom integrations', $name = 'itemprop');

        $this->OpenGraph->setTitle('Billing software for ISP');
        $this->OpenGraph->setDescription('Online billing software for internet service providers with subscriber management, online payments  and package selection for customers.');
        $this->OpenGraph->setUrl('https://www.swipez.in/billing-software-for-educational-institute');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();
        JsonLd::setTitle('Billing software for internet service providers ');
        JsonLd::setDescription('Optimizing the most precious business aspect for a utility provider - Timely revenue collections.');
        JsonLd::setImage('https://www.swipez.in/assets/swipez-web/images/product/billing-software/industry/isp.svg');
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.5","reviewCount":"99"}', 1));
        $jsonld = JsonLd::generate();
        $this->smarty->assign("meta", $meta . $og . $jsonld);
        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/industry/isp.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for education industry landing page
     *
     * @return void
     */
    public function education() {
        SEOMeta::setTitle('Billing software for education institutes');
        SEOMeta::setDescription('Organize billing, fee collection for your courses and venue booking management for your facilities.');
        SEOMeta::addKeyword(['education billing software', 'online invoicing software', 'online billing system', 'online invoice', 'venue management', 'billing software', 'invoicing with online payments', 'billing with online payments']);
        SEOMeta::addMeta('name', $value = 'Billing software for education institutes', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Billing solution for educational institutes with venue booking software', $name = 'itemprop');

        $this->OpenGraph->setTitle('Billing software for education institutes');
        $this->OpenGraph->setDescription('Organize billing, fee collection for your courses and venue booking management for your facilities.');
        $this->OpenGraph->setUrl('https://www.swipez.in/billing-software-for-educational-institute');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();
        JsonLd::setTitle('Billing software for education institute');
        JsonLd::setDescription('Optimizing the most precious business aspect for a utility provider - Timely revenue collections by using billing software.');
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/industry/education.svg');
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.5","reviewCount":"99"}', 1));
        $jsonld = JsonLd::generate();
        $this->smarty->assign("meta", $meta . $og . $jsonld);
        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/industry/education.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for travelntour industry landing page
     *
     * @return void
     */
    public function travelntour() {
        SEOMeta::setTitle('Billing software for travel and tour operators');
        SEOMeta::setDescription('Online billing software for travel and tour operators with invoicing, payment reminders, GST filing and payouts.');
        SEOMeta::addKeyword(['travel and tour operator billing software', 'online invoicing software', 'online billing system', 'online invoice', 'GST filing', 'billing software', 'invoicing with online payments', 'billing with online payments']);
        SEOMeta::addMeta('name', $value = 'Billing software for travel and tour operators', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Billing solution for travel and tour operators servicing corporates or individual customers', $name = 'itemprop');

        $this->OpenGraph->setTitle('Billing software for travel and tour operators');
        $this->OpenGraph->setDescription('Online billing software for travel and tour operators with invoicing, payment reminders, GST filing and payouts.');
        $this->OpenGraph->setUrl('https://www.swipez.in/billing-software-for-travel-and-tour-operator');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();

        JsonLd::setTitle('Billing software for travel and tour operators');
        JsonLd::setDescription('Organize your invoicing, collect payments faster and manage pay outs with ease.');
        JsonLd::setImage('https://www.swipez.in/images/product/billing-software/industry/travel-tour-operator.svg');
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.6","reviewCount":"109"}', 1));
        $jsonld = JsonLd::generate();
        $this->smarty->assign("meta", $meta . $og . $jsonld);
        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/industry/travelntour.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for entertainment event industry landing page
     *
     * @return void
     */
    public function entertainmentevent() {
        SEOMeta::setTitle('Event registration software for entertainment events');
        SEOMeta::setDescription('Online event management software for entertainment event organizers to create events, collect online payments, manage box office for their events.');
        SEOMeta::addKeyword(['event ticketing platform', 'event management platform', 'event management software', 'event planning software', 'event registration software', 'event management']);
        SEOMeta::addMeta('name', $value = 'Event registration software for entertainment events', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Online event registration and ticketing platform for entertainment events', $name = 'itemprop');

        $this->OpenGraph->setTitle('Event registration software for entertainment events');
        $this->OpenGraph->setDescription('Online event management software for entertainment event organizers to create events, collect online payments, manage box office for their events.');
        $this->OpenGraph->setUrl('https://www.swipez.in/event-registration-for-entertainment-event');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();
        JsonLd::setTitle('Event registration and ticketing for entertainment');
        JsonLd::setDescription('Create modern event landing pages, allow customers to book tickets and make payments online.');
        JsonLd::setImage('https://www.swipez.in/assets/swipez-web/images/product/event-registration/industry/entertainment.svg');
        JsonLd::addValue('review', json_decode('{"@type":"Review","reviewRating":{"@type":"Rating","ratingValue":"4","bestRating":"5"},"author":{"@type":"Person","name":"Mahesh Shelote "}}', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.8","reviewCount":"155"}', 1));
        $jsonld = JsonLd::generate();
        $this->smarty->assign("meta", $meta . $og . $jsonld);
        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/industry/entertainmentevent.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for hospitality event industry landing page
     *
     * @return void
     */
    public function hospitalityevent() {
        SEOMeta::setTitle('Event registration software for hospitality events');
        SEOMeta::setDescription('Online event management software for hospitality event organizers to create events, collect online payments, manage box office for their events.');
        SEOMeta::addKeyword(['event ticketing platform', 'event management platform', 'event management software', 'event planning software', 'event registration software', 'event management']);
        SEOMeta::addMeta('name', $value = 'Event registration software for hospitality events', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Online event registration and ticketing platform for hospitality events', $name = 'itemprop');

        $this->OpenGraph->setTitle('Event registration software for hospitality events');
        $this->OpenGraph->setDescription('Online event management software for hospitality event organizers to create events, collect online payments, manage box office for their events.');
        $this->OpenGraph->setUrl('https://www.swipez.in/event-registration-for-hospitality-event');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();
        JsonLd::setTitle('Event registration and bookings for hospitality events');
        JsonLd::setDescription('Create beautiful landing pages for your hospitality event and let your customers book and pay online.');
        JsonLd::setImage('https://www.swipez.in/assets/swipez-web/images/product/event-registration/industry/hospitality.svg');
        JsonLd::addValue('review', json_decode('{"@type":"Review","reviewRating":{"@type":"Rating","ratingValue":"4","bestRating":"5"},"author":{"@type":"Person","name":"Aniruddha Patil"}}', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.8","reviewCount":"158"}', 1));
        $jsonld = JsonLd::generate();
        $this->smarty->assign("meta", $meta . $og . $jsonld);
        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/industry/hospitalityevent.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for health and fitness bookings industry landing page
     *
     * @return void
     */
    public function venuebookingfitness() {
        SEOMeta::setTitle('Venue booking software for health and fitness');
        SEOMeta::setDescription('Manage times slots for your venues, collect payments online and publish your venue calendar link. Ideal for courts, studios, gyms and spaces at your venue.');
        SEOMeta::addKeyword(['venue management software', 'venue scheduling software', 'venue booking software', 'facility management software', 'facility scheduling software', 'facility booking software', 'room scheduling software', 'resource scheduling software', 'time slot booking software']);
        SEOMeta::addMeta('name', $value = 'Venue booking software for health and fitness', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Health and fitness venue booking and time slot management', $name = 'itemprop');

        $this->OpenGraph->setTitle('Venue booking software for health and fitness');
        $this->OpenGraph->setDescription('Manage times slots for your venues, collect payments online and publish your venue calendar link. Ideal for courts, studios, gyms and spaces at your venue.');
        $this->OpenGraph->setUrl('https://www.swipez.in/venue-booking-management-for-health-and-fitness');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();
        JsonLd::setTitle('Venue booking management for health & fitness');
        JsonLd::setDescription('Our online time slot & calendar based booking system helps you focus on the things you love.');
        JsonLd::setImage('https://www.swipez.in/assets/swipez-web/images/product/venue-booking-software/industry/health-fitness.svg');
        JsonLd::addValue('review', json_decode('{"@type":"Review","reviewRating":{"@type":"Rating","ratingValue":"4","bestRating":"5"},"author":{"@type":"Person","name":"Chandra Gupta"}}', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.4","reviewCount":"105"}', 1));
        $jsonld = JsonLd::generate();
        $this->smarty->assign("meta", $meta . $og . $jsonld);
        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/industry/venuebookingfitness.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for real estate bookings industry landing page
     *
     * @return void
     */
    public function societybooking() {
        SEOMeta::setTitle('Billing software for housing society');
        SEOMeta::setDescription('Manage maintenance bills, provide venue bookings online, setup memberships and provide online payment options to residents of your housing society.');
        SEOMeta::addKeyword(['housing society billing software', 'online invoicing software', 'online billing system', 'venue booking facility for housing society', 'membership management']);
        SEOMeta::addMeta('name', $value = 'Billing booking software for housing society', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Setup billing and venue booking management for your housing society', $name = 'itemprop');

        $this->OpenGraph->setTitle('Billing booking software for housing society');
        $this->OpenGraph->setDescription('Manage maintenance bills, provide venue bookings online, setup memberships and provide online payment options to residents of your housing society');
        $this->OpenGraph->setUrl('https://www.swipez.in//billing-and-venue-booking-software-for-housing-societies');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();
        JsonLd::setTitle('Billing & venue booking software for housing societies');
        JsonLd::setDescription('Organizing amenity booking, memberships and payment collections for housing societies.');
        JsonLd::setImage('https://www.swipez.in/assets/swipez-web/images/product/venue-booking-software/industry/housing-society.svg');
        JsonLd::addValue('review', json_decode('{"@type":"Review","reviewRating":{"@type":"Rating","ratingValue":"4","bestRating":"5"},"author":{"@type":"Person","name":"Vikas Sankhla"}}', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.7","reviewCount":"108"}', 1));
        $jsonld = JsonLd::generate();
        $this->smarty->assign("meta", $meta . $og . $jsonld);
        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/industry/societybooking.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for utility providers short url industry landing page
     *
     * @return void
     */
    public function utilityprovider() {
        SEOMeta::setTitle('');
        SEOMeta::setDescription('');
        SEOMeta::addKeyword([]);
        SEOMeta::addMeta('application-name', $value = '', $name = 'name');
        SEOMeta::addMeta('name', $value = '', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = '', $name = 'itemprop');

        $this->OpenGraph->setTitle('');
        $this->OpenGraph->setDescription('');
        $this->OpenGraph->setUrl('');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();
        $this->smarty->assign("meta", $meta . $og);
        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/industry/utilityprovider.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for financial services short url industry landing page
     *
     * @return void
     */
    public function enterprises() {
        SEOMeta::setTitle('URL shortener for enterprise');
        SEOMeta::setDescription('URL shortener with custom domains for enterprises with large or small volumes. Excel uploads and API integrations supported.');
        SEOMeta::addKeyword(['shorten long urls', 'url shortener', 'URL redirect', 'branded tiny url', 'branded short url']);
        SEOMeta::addMeta('name', $value = 'Swipez URL shortener', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'URL shortener for enterprise', $name = 'itemprop');

        $this->OpenGraph->setTitle('URL shortener for enterprise');
        $this->OpenGraph->setDescription('URL shortener with custom domains for enterprises with large or small volumes. Excel uploads and API integrations supported');
        $this->OpenGraph->setUrl('https://www.swipez.in/short-url-solution-for-enterprise');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();
        JsonLd::setTitle('URL Shortener for enterprises');
        JsonLd::setDescription('Create branded short links for all your customer communications at scale and within budget.');
        JsonLd::setImage('https://www.swipez.in/assets/swipez-web/images/product/url-shortener.svg');
        JsonLd::addValue('review', json_decode('{"@type":"Review","reviewRating":{"@type":"Rating","ratingValue":"4","bestRating":"5"},"author":{"@type":"Person","name":"Lokesh Sonawane"}}', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.7","reviewCount":"135"}', 1));
        $jsonld = JsonLd::generate();
        $this->smarty->assign("meta", $meta . $og . $jsonld);
        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/industry/enterprises.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for freelancers landing page
     *
     * @return void
     */
    public function freelancers() {
        SEOMeta::setTitle('Billing software for consultancy firms and freelancers');
        SEOMeta::setDescription('Free online billing software for consultancy firms, startup and freelancers with online payment collections');
        SEOMeta::addKeyword(['billing software', 'online invoicing software', 'online billing system', 'online invoice', 'subscriber management', 'billing software', 'invoicing with online payments', 'billing with online payments']);
        SEOMeta::addMeta('name', $value = 'Billing software for consultancy firms and freelancers', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Billing solution for consultants, freenlancers, startup. Start with our free plan.', $name = 'itemprop');

        $this->OpenGraph->setTitle('Billing software for consultancy firms and freelancers');
        $this->OpenGraph->setDescription('Free online billing software for consultancy firms, startup and freelancers with online payment collections');
        $this->OpenGraph->setUrl('https://www.swipez.in/invoicing-software-for-freelancers-and-consultants');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();

        JsonLd::setTitle('Billing software for consultancy firms and freelancers');
        JsonLd::setDescription('Enabling timely revenue collections for your hard work.');
        JsonLd::setImage('https://www.swipez.in/assets/swipez-web/images/product/billing-software/industry/freelancer.svg');
        JsonLd::addValue('review', json_decode('{"@type":"Review","reviewRating":{"@type":"Rating","ratingValue":"4","bestRating":"5"},"author":{"@type":"Person","name":"Avinash Agarwal"}}', 1));
        JsonLd::addValue('aggregateRating', json_decode('{"@type":"AggregateRating","ratingValue":"4.7","reviewCount":"108"}', 1));
        $jsonld = JsonLd::generate();
        $this->smarty->assign("meta", $meta . $og . $jsonld);
        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/industry/freelancers.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for pricing landing page
     *
     * @return void
     */
    public function pricing() {
        SEOMeta::setTitle('Swipez pricing | Free plans available with all our products');
        SEOMeta::setDescription('A family of products for payment collections. From startups to enterprises, Swipez has pricing plans for any organization.');
        SEOMeta::addMeta('name', $value = 'Swipez pricing', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Free plans available with all our products', $name = 'itemprop');

        $this->OpenGraph->setTitle('Swipez pricing');
        $this->OpenGraph->setDescription('A family of products for payment collections. From startups to enterprises, Swipez has pricing plans for any organization.');
        $this->OpenGraph->setUrl('https://www.swipez.in/pricing');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();
        $this->smarty->assign("meta", $meta . $og);
        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/pricing.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for billing pricing landing page
     *
     * @return void
     */
    public function billingpricing() {
        SEOMeta::setTitle('Billing software pricing | Free forever plan available.');
        SEOMeta::setDescription('Buy the best online billing software for your business at the most affordable prices. Forever free plan available.');
        SEOMeta::addKeyword(['affordable invoicing', 'online invoicing software', 'online billing system', 'online invoice', 'invoice', 'billing software', 'invoicing with online payments', 'billing with online payments']);
        SEOMeta::addMeta('name', $value = 'Swipez Billing software pricing', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Billing software pricing | Free forever plan available.', $name = 'itemprop');

        $this->OpenGraph->setTitle('Swipez Billing software pricing');
        $this->OpenGraph->setDescription('Buy the best online billing software for your business at the most affordable prices. Forever free plan available.');
        $this->OpenGraph->setUrl('https://www.swipez.in/billing-software-pricing');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();
        $this->smarty->assign("meta", $meta . $og);
        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/pricing/billing.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for event pricing landing page
     *
     * @return void
     */
    public function eventpricing() {
        SEOMeta::setTitle('Event registration software pricing | Free forever plan available.');
        SEOMeta::setDescription('View pricing and plans for online event registration and ticketing software. Free forever plans available.');
        SEOMeta::addKeyword(['affordable event management', 'online event registration software', 'online ticketing system', 'free online event hosting']);
        SEOMeta::addMeta('name', $value = 'Swipez Event registration software pricing', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Billing software pricing | Free forever plan available.', $name = 'itemprop');

        $this->OpenGraph->setTitle('Event registration software pricing | Free forever plan available.');
        $this->OpenGraph->setDescription('View pricing and plans for online event registration and ticketing software. Free forever plans available.');
        $this->OpenGraph->setUrl('https://www.swipez.in/event-registration-pricing');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();
        $this->smarty->assign("meta", $meta . $og);
        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/pricing/event.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for booking calendar pricing landing page
     *
     * @return void
     */
    public function bookingcalendarpricing() {
        SEOMeta::setTitle('Venue booking software pricing | Free forever plan available.');
        SEOMeta::setDescription('View pricing & plans for venue booking software. Free forever plans available.');
        SEOMeta::addKeyword(['affordable venue booking software', 'online venue booking software', 'online venue time slot booking system', 'free venue booking software']);
        SEOMeta::addMeta('name', $value = 'Swipez Venue booking software pricing', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Venue booking software pricing. Free forever plan available.', $name = 'itemprop');

        $this->OpenGraph->setTitle('Venue booking software pricing | Free forever plan available.');
        $this->OpenGraph->setDescription('View pricing & plans for venue booking software. Free forever plans available.');
        $this->OpenGraph->setUrl('https://www.swipez.in/venue-booking-software-pricing');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();
        $this->smarty->assign("meta", $meta . $og);
        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/pricing/bookingcalendar.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for website builder pricing landing page
     *
     * @return void
     */
    public function websitebuilderpricing() {
        SEOMeta::setTitle('Website builder pricing | Free forever plan available.');
        SEOMeta::setDescription('View pricing & plans for website builder. Free forever plans available.');
        SEOMeta::addKeyword(['free website builder', 'affordable website builder', 'online website builder', 'free website with online payments']);
        SEOMeta::addMeta('name', $value = 'Swipez Website builder pricing', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Website builder pricing. Free forever plan available.', $name = 'itemprop');

        $this->OpenGraph->setTitle('Website builder pricing | Free forever plan available.');
        $this->OpenGraph->setDescription('View pricing & plans for website builder. Free forever plans available.');
        $this->OpenGraph->setUrl('https://www.swipez.in/website-builder-pricing');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();
        $this->smarty->assign("meta", $meta . $og);
        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/pricing/websitebuilder.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for url shortener pricing landing page
     *
     * @return void
     */
    public function urlshortenerpricing() {
        SEOMeta::setTitle('URL shortener pricing');
        SEOMeta::setDescription('View pricing and plans for URL shortener. Affordable pricing plans for large or small volumes.');
        SEOMeta::addKeyword(['url shortener pricing', 'custom domain url shortener pricing', 'affordable url shortener']);
        SEOMeta::addMeta('name', $value = 'Swipez URL shortener pricing', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Affordable pricing plans for enterprise URL shortener.', $name = 'itemprop');

        $this->OpenGraph->setTitle('URL shortener pricing');
        $this->OpenGraph->setDescription('View pricing and plans for URL shortener. Affordable pricing plans for large or small volumes.');
        $this->OpenGraph->setUrl('https://www.swipez.in/url-shortener-pricing');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();
        $this->smarty->assign("meta", $meta . $og);
        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/pricing/urlshortener.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for online transactions pricing landing page
     *
     * @return void
     */
    public function paymentgatewaycharges() {
        SEOMeta::setTitle('Payment gateway charges. Charges starting at 0%');
        SEOMeta::setDescription('Payment gateway charges with 0 setup fee, 0 maintenance cost, TDRs starting at 0%');
        SEOMeta::addKeyword(['payment gateway charges', 'payment gateway charges india', 'payment gateway pricing']);
        SEOMeta::addMeta('name', $value = 'Swipez payment gateway charges', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Payment gateway charges. Charges starting at 0%', $name = 'itemprop');

        $this->OpenGraph->setTitle('Payment gateway charges. Charges starting at 0%');
        $this->OpenGraph->setDescription('Payment gateway charges with 0 setup fee, 0 maintenance cost, TDRs starting at 0%');
        $this->OpenGraph->setUrl('https://www.swipez.in/payment-gateway-charges');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();
        $this->smarty->assign("meta", $meta . $og);
        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/pricing/onlinetransactions.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for Swipez terms
     *
     * @return void
     */
    public function terms() {
        SEOMeta::setTitle('Swipez terms of service');
        SEOMeta::setDescription('Terms of service for Swipez products');
        SEOMeta::addMeta('name', $value = 'Swipez terms of service', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Terms of service for Swipez products', $name = 'itemprop');

        $this->OpenGraph->setTitle('Swipez terms of service');
        $this->OpenGraph->setDescription('Terms of service for Swipez products');
        $this->OpenGraph->setUrl('https://www.swipez.in/terms');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();
        $this->smarty->assign("meta", $meta . $og);
        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/footer/terms.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for Swipez terms
     *
     * @return void
     */
    public function termspopup() {
        SEOMeta::setTitle('Swipez terms of service');
        SEOMeta::setDescription('Terms of service for Swipez products');
        SEOMeta::addMeta('name', $value = 'Swipez terms of service', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Terms of service for Swipez products', $name = 'itemprop');

        $this->OpenGraph->setTitle('Swipez terms of service');
        $this->OpenGraph->setDescription('Terms of service for Swipez products');
        $this->OpenGraph->setUrl('https://www.swipez.in/terms-popup');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();
        $this->smarty->assign("meta", $meta . $og);
        $this->smarty->display(VIEW . 'swipezweb/popup.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/footer/termspopup.tpl');
        //$this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for Swipez privacy
     *
     * @return void
     */
    public function privacypopup() {
        SEOMeta::setTitle('Swipez privacy policy');
        SEOMeta::setDescription('Privacy policy for Swipez products');
        SEOMeta::addMeta('name', $value = 'Swipez privacy policy', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Privacy policy for Swipez products', $name = 'itemprop');

        $this->OpenGraph->setTitle('Swipez privacy policy');
        $this->OpenGraph->setDescription('Privacy policy for Swipez products');
        $this->OpenGraph->setUrl('https://www.swipez.in/privacy-popup');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();
        $this->smarty->assign("meta", $meta . $og);
        $this->smarty->display(VIEW . 'swipezweb/popup.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/footer/privacypopup.tpl');
        //$this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for Swipez privacy
     *
     * @return void
     */
    public function privacy() {
        SEOMeta::setTitle('Swipez privacy policy');
        SEOMeta::setDescription('Privacy policy for Swipez products');
        SEOMeta::addMeta('name', $value = 'Swipez privacy policy', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Privacy policy for Swipez products', $name = 'itemprop');

        $this->OpenGraph->setTitle('Swipez privacy policy');
        $this->OpenGraph->setDescription('Privacy policy for Swipez products');
        $this->OpenGraph->setUrl('https://www.swipez.in/privacy');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();
        $this->smarty->assign("meta", $meta . $og);
        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/footer/privacy.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for Swipez about us
     *
     * @return void
     */
    public function aboutus() {
        SEOMeta::setTitle('Swipez about us');
        SEOMeta::setDescription('Learn something about us');
        SEOMeta::addMeta('name', $value = 'Swipez about us', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Learn something about us', $name = 'itemprop');

        $this->OpenGraph->setTitle('Swipez about us');
        $this->OpenGraph->setDescription('Learn something about us');
        $this->OpenGraph->setUrl('https://www.swipez.in/about-us');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();
        $this->smarty->assign("meta", $meta . $og);
        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/footer/aboutus.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for Swipez about us
     *
     * @return void
     */
    public function contactus() {
        SEOMeta::setTitle('Swipez contact us');
        SEOMeta::setDescription('Want to get in touch?');
        SEOMeta::addMeta('name', $value = 'Swipez contact us', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Want to get in touch?', $name = 'itemprop');

        $this->OpenGraph->setTitle('Swipez contact us');
        $this->OpenGraph->setDescription('Want to get in touch?');
        $this->OpenGraph->setUrl('https://www.swipez.in/contactus');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();
        $this->smarty->assign("meta", $meta . $og);
        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/footer/contactus.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for Swipez Get in touch
     *
     * @return void
     */
    public function getintouch($subject = null) {
        SEOMeta::setTitle('Swipez contact us');
        SEOMeta::setDescription('Want to get in touch?');
        SEOMeta::addMeta('name', $value = 'Swipez contact us', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Want to get in touch?', $name = 'itemprop');

        $this->OpenGraph->setTitle('Swipez contact us');
        $this->OpenGraph->setDescription('Want to get in touch?');
        $this->OpenGraph->setUrl('https://www.swipez.in/getintouch/' . $subject);
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();
        $this->smarty->assign("meta", $meta . $og);
        $this->smarty->assign("subject", $subject);
        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/footer/getintouch.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for Swipez partner
     *
     * @return void
     */
    public function partner() {
        SEOMeta::setTitle('Swipez partner program');
        SEOMeta::setDescription('Partner with Swipez and earn a recurring income');
        SEOMeta::addMeta('name', $value = 'Swipez partner program', $name = 'itemprop');
        SEOMeta::addMeta('description', $value = 'Partner with Swipez and earn a recurring income', $name = 'itemprop');

        $this->OpenGraph->setTitle('Swipez partner program');
        $this->OpenGraph->setDescription('Partner with Swipez and earn a recurring income');
        $this->OpenGraph->setUrl('https://www.swipez.in/partner');
        $meta = SEOMeta::generate();
        $og = $this->OpenGraph->generate();
        $this->smarty->assign("meta", $meta . $og);
        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/home/footer/partner.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

    /**
     * Returns view for Swipez partner
     *
     * @return void
     */
    public function pagenotfound() {
        $this->smarty->display(VIEW . 'swipezweb/master.tpl');
        $this->smarty->display(VIEW . 'swipezweb/404.tpl');
        $this->smarty->display(VIEW . 'swipezweb/footer.tpl');
    }

}
