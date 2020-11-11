-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 21, 2020 at 07:39 AM
-- Server version: 5.6.43
-- PHP Version: 5.6.40
USE seat_reservation;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `seat_reservation`
--

-- --------------------------------------------------------

--
-- Table structure for table `buildings`
--

CREATE TABLE `buildings` (
  `building_id` int(11) NOT NULL,
  `building_name` varchar(225) DEFAULT NULL,
  `building_address` varchar(225) DEFAULT NULL,
  `description` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `chat_id` int(10) UNSIGNED NOT NULL,
  `chat_unique_id` varchar(225) NOT NULL,
  `sender_id` int(10) UNSIGNED NOT NULL,
  `receiver_id` int(10) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `read_status` enum('0','1') NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `delete_users`
--

CREATE TABLE `delete_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role` enum('1','2','3') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1=admin,2=users,3=driver',
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `job_profile` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verify_status` enum('0','1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_image` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lng` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_verify_status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '0=otp_not_verified,1=otp_verified',
  `active_status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '0=deactivate,1 activate',
  `approve_status` enum('0','1','2') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '0=pending,1=approved,2=rejected',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notification_status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '0=off,1=on',
  `device_type` enum('android','ios','web','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_token` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language` enum('en','es') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `logo_image` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(10) UNSIGNED NOT NULL,
  `sender_id` int(10) UNSIGNED NOT NULL,
  `receiver_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(225) DEFAULT NULL,
  `message` varchar(225) DEFAULT NULL,
  `json_data` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `offices`
--

CREATE TABLE `offices` (
  `office_id` int(10) UNSIGNED NOT NULL,
  `building_id` int(10) UNSIGNED NOT NULL,
  `office_name` varchar(225) DEFAULT NULL,
  `office_number` varchar(225) DEFAULT NULL,
  `description` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `office_images`
--

CREATE TABLE `office_images` (
  `office_image_id` int(10) UNSIGNED NOT NULL,
  `office_id` int(10) UNSIGNED NOT NULL,
  `image` varchar(225) DEFAULT NULL,
  `description` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `office_seats`
--

CREATE TABLE `office_seats` (
  `id` int(11) UNSIGNED NOT NULL,
  `office_id` int(11) NOT NULL,
  `number` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `booking_mode` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `page_id` int(11) NOT NULL,
  `type` enum('1','2') NOT NULL COMMENT '1==about_us,2==terms&conditions',
  `content` longtext NOT NULL,
  `content_es` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`page_id`, `type`, `content`, `content_es`) VALUES
(1, '1', '<div class=\"body\" style=\"padding: 10px 15px;\">\r\n	<h3 class=\"section-heading\" style=\"text-align: center; font-size: 22px;\">Privacy Policy</h3>\r\n\r\n	<!-------------------Englis pp--------------------->\r\n	<h4 class=\"sub-heading french\">B&A GROUP built the YoPO a Commercial app. This SERVICE is provided by B&A GROUP and is intended for use as is.</h4>\r\n\r\n	<p>This page is used to inform visitors regarding our policies with the collection, use, and disclosure of Personal Information if anyone decided to use our Service.</p>\r\n\r\n	<p>If you choose to use our Service, then you agree to the collection and use of information in relation to this policy. The Personal Information that we collect is used for providing and improving the Service. We will not use or share your information with anyone except as described in this Privacy Policy.</p>\r\n\r\n	<p>The terms used in this Privacy Policy have the same meanings as in our Terms and Conditions, which is accessible at YoPo unless otherwise defined in this Privacy Policy.</p>\r\n\r\n\r\n\r\n	<h4 class=\"sub-heading\">Information Collection and Use</h4>\r\n	<p>For a better experience, while using our Service, we may require you to provide us with certain personally identifiable information, including but not limited to Username, Password, adrees, social security number or tax id number  Mobile number. id witth picture, and one selfie. The information that we request will be retained by us and used as described in this privacy policy.</p>\r\n\r\n	<p>The app does use third party services that may collect information used to identify you.</p>\r\n\r\n	<p>Link to privacy policy of third party service providers used by the app</p>\r\n\r\n	<p>Payment will be charged to iTunes Account at confirmation of purchase</p>\r\n\r\n	<p>Subscription automatically renews unless auto-renew is turned off at least 24-hours before the end of the current period\r\n	Account will be charged for renewal within 24-hours prior to the end of the current period, and identify the cost of the renewal\r\n	Subscriptions may be managed by the user and auto-renewal may be turned off by going to the user\'s Account Settings after purchase</p>\r\n	\r\n	<h4 class=\"sub-heading\">Log Data</h4>\r\n	<p>We want to inform you that whenever you use our Service, in a case of an error in the app we collect data and information (through third party products) on your phone called Log Data. This Log Data may include information such as your device Internet Protocol (“IP”) address, device name, operating system version, the configuration of the app when utilizing our Service, the time and date of your use of the Service, and other statistics.</p>\r\n\r\n	<h4 class=\"sub-heading\">Cookies</h4>\r\n\r\n	<p>Cookies are files with a small amount of data that are commonly used as anonymous unique identifiers. These are sent to your browser from the websites that you visit and are stored on your device\'s internal memory.</p>\r\n\r\n	<p>This Service does not use these “cookies” explicitly. However, the app may use third party code and libraries that use “cookies” to collect information and improve their services. You have the option to either accept or refuse these cookies and know when a cookie is being sent to your device. If you choose to refuse our cookies, you may not be able to use some portions of this Service.</p>\r\n\r\n	<h4 class=\"sub-heading\">Service Providers</h4>\r\n\r\n	<p>We may employ third-party</p>\r\n\r\<p>companies and individuals due to the following reasons:</p>\r\n\r\n	<ul>\r\n		<li>To provide the Service on our behalf;</li>\r\n		<li>To perform Service-related services; or</li>\r\n		<li>To assist us in analyzing how our Service is used.</li>\r\n		<li>We want to inform users of this Service that these third parties have access to your Personal Information. The reason is to perform the tasks assigned to them on our behalf. However, they are obligated not to disclose or use the information for any other purpose.</li>\r\n	</ul>\r\n\r\n	<h4 class=\"sub-heading\">Security</h4>\r\n\r\n	<p>We value your trust in providing us your Personal Information, thus we are striving to use commercially acceptable means of protecting it. But remember that no method of transmission over the internet, or method of electronic storage is 100% secure and reliable, and we cannot guarantee its absolute security.</p>\r\n\r\n	<h4 class=\"sub-heading\">Links to Other Sites</h4>\r\n	<p>This Service may contain links to other sites. If you click on a third-party link, you will be directed to that site. Note that these external sites are not operated by us. Therefore, we strongly advise you to review the Privacy Policy of these websites. We have no control over and assume no responsibility for the content, privacy policies, or practices of any third-party sites or services.</p>\r\n\r\n	<h4 class=\"sub-heading\">Children’s Privacy</h4>\r\n	<p>These Services do not address anyone under the age of 18. We do not knowingly collect personally identifiable information from children under 18. In the case we discover that a child under 13 has provided us with personal information, we immediately delete this from our servers. If you are a parent or guardian and you are aware that your child has provided us with personal information, please contact us so that we will be able to do necessary actions.</p>\r\n\r\n	<h4 class=\"sub-heading\">Changes to This Privacy Policy</h4>\r\n	<p>We may update our Privacy Policy from time to time. Thus, you are advised to review this page periodically for any changes. We will notify you of any changes by posting the new Privacy Policy on this page. These changes are effective immediately after they are posted on this page.</p>\r\n\r\n	<h4 class=\"sub-heading\">Contact Us</h4>\r\n	<p>If you have any questions or suggestions about our Privacy Policy, do not hesitate to contact us at <a href=\"mailto:contact@yopo.us\">contact@yopo.us</a></p>\r\n</div>', '<div class=\"body\" style=\"padding: 10px 15px;\">\r\n	<h3 class=\"section-heading\" style=\"text-align: center; font-size: 22px;\">Política de privacidad</h3>\r\n\r\n	<!-------------------french pp--------------------->\r\<h4 class=\"sub-heading french\">B&A GROUP creó YoPO, una aplicación comercial. Este SERVICIO es proporcionado por B&A GROUP y está diseñado para usarse tal cual.</h4>\r\n\r\n	<p>Esta página se utiliza para informar a los visitantes sobre nuestras políticas con la recopilación, uso y divulgación de información personal si alguien decide utilizar nuestro servicio.</p>\r\n\r\n	<p>Si elige utilizar nuestro Servicio, entonces acepta la recopilación y el uso de información en relación con esta política. La información personal que recopilamos se utiliza para proporcionar y mejorar el Servicio. No utilizaremos ni compartiremos su información con nadie, excepto como se describe en esta Política de privacidad.</p>\r\n\r\n	<p>Los términos utilizados en esta Política de privacidad tienen el mismo significado que en nuestros Términos y condiciones, a los que se puede acceder en YoPo a menos que se defina lo contrario en esta Política de privacidad.</p>\r\n\r\n\r\n\r\n	<h4 class=\"sub-heading\">Recolección de información y uso</h4>\r\n\r\n	<p>Para una mejor experiencia, mientras utiliza nuestro Servicio, es posible que le solicitemos que nos brinde cierta información de identificación personal, que incluye, entre otros, Nombre de usuario, Contraseña, destinatarios, número de seguro social o número de identificación fiscal Número de teléfono móvil. Identificación con foto y una selfie. La información que solicitamos será retenida por nosotros y utilizada como se describe en esta política de privacidad.</p>\r\n\r\n	<p>La aplicación utiliza servicios de terceros que pueden recopilar información utilizada para identificarlo.</p>\r\n\r\n	<p>Enlace a la política de privacidad de terceros proveedores de servicios utilizados por la aplicación</p>\r\n\r\n	<p>Suscripción Auto Renovable</p>\r\n\r\n	<p>El pago se cargará a la cuenta de iTunes en la confirmación de la compra.</p>\r\n\r\n	<p>La suscripción se renueva automáticamente a menos que la renovación automática esté desactivada al menos 24 horas antes del final del período actual\r\n	Se le cobrará a la cuenta por la renovación dentro de las 24 horas anteriores al final del período actual e identificará el costo de la renovación\r\n	Las suscripciones pueden ser administradas por el usuario y la renovación automática puede desactivarse yendo a la Configuración de la cuenta del usuario después de la compra</p>\r\n	\r\n	<h4 class=\"sub-heading\">Dato de registro</h4>\r\n	<p>Queremos informarle que cada vez que utiliza nuestro Servicio, en caso de un error en la aplicación, recopilamos datos e información (a través de productos de terceros) en su teléfono llamados Datos de registro. Estos Datos de registro pueden incluir información como la dirección del Protocolo de Internet (\"IP\") de su dispositivo, el nombre del dispositivo, la versión del sistema operativo, la configuración de la aplicación cuando utiliza nuestro Servicio, la hora y la fecha de uso del Servicio y otras estadísticas.</p>\r\n\r\n	<h4 class=\"sub-heading\">Galletas</h4>\r\n\r\n	<p>Las cookies son archivos con una pequeña cantidad de datos que se usan comúnmente como identificadores únicos anónimos. Estos se envían a su navegador desde los sitios web que visita y se almacenan en la memoria interna de su dispositivo.</p>\r\n\r\n	<p>Este servicio no utiliza estas \"cookies\" explícitamente. Sin embargo, la aplicación puede usar código de terceros y bibliotecas que usan \"cookies\" para recopilar información y mejorar sus servicios. Tiene la opción de aceptar o rechazar estas cookies y saber cuándo se envía una cookie a su dispositivo. Si elige rechazar nuestras cookies, es posible que no pueda usar algunas partes de este Servicio.</p>\r\n\r\n	<h4 class=\"sub-heading\">Proveedores de servicio</h4>\r\n\r\n	<p>Podemos emplear a terceros</p>\r\n\r\n	<p>empresas y particulares por los siguientes motivos:</p>\r\n\r\n	<ul>\r\n		<li>Para facilitar nuestro servicio;</li>\r\n		<li>Para proporcionar el Servicio en nuestro nombre;</li>\r\n		<li>Para realizar servicios relacionados con el Servicio; o</li>\r\n		<li>Para ayudarnos a analizar cómo se utiliza nuestro Servicio.</li>\r\<li>Queremos informar a los usuarios de este Servicio que estos terceros tienen acceso a su Información personal. La razón es realizar las tareas que se les asignaron en nuestro nombre. Sin embargo, están obligados a no divulgar ni utilizar la información para ningún otro propósito.</li>\r\n	</ul>\r\n\r\n	<h4 class=\"sub-heading\">Seguridad</h4>\r\n\r\n	<p>Valoramos su confianza en proporcionarnos su información personal, por lo tanto, nos esforzamos por utilizar medios comercialmente aceptables para protegerla. Pero recuerde que ningún método de transmisión por Internet o método de almacenamiento electrónico es 100% seguro y confiable, y no podemos garantizar su seguridad absoluta.</p>\r\n\r\n	<h4 class=\"sub-heading\">Enlaces a otros sitios</h4>\r\n\r\n	<p>Este servicio puede contener enlaces a otros sitios. Si hace clic en un enlace de un tercero, será dirigido a ese sitio. Tenga en cuenta que estos sitios externos no son operados por nosotros. Por lo tanto, le recomendamos encarecidamente que revise la Política de privacidad de estos sitios web. No tenemos control ni asumimos ninguna responsabilidad por el contenido, las políticas de privacidad o las prácticas de sitios o servicios de terceros.</p>\r\n\r\n	<h4 class=\"sub-heading\">Privacidad de los niños</h4>\r\n	<p>Estos servicios no se dirigen a ninguna persona menor de 18 años. No recopilamos a sabiendas información de identificación personal de niños menores de 18 años. En el caso de que descubramos que un niño menor de 13 años nos ha proporcionado información personal, la eliminamos de inmediato de nuestros servidores. Si usted es padre o tutor y sabe que su hijo nos ha proporcionado información personal, contáctenos para que podamos realizar las acciones necesarias.</p>\r\n\r\n<h4 class=\"sub-heading\">Cambios a esta política de privacidad</h4>\r\n	<p>Podemos actualizar nuestra Política de privacidad de vez en cuando. Por lo tanto, se recomienda que revise esta página periódicamente en busca de cambios. Le notificaremos cualquier cambio publicando la nueva Política de privacidad en esta página. Estos cambios son efectivos inmediatamente después de su publicación en esta página.</p>\r\n\r\n	<h4 class=\"sub-heading\">Contact Us</h4>\r\n	<p>Si tiene alguna pregunta o sugerencia sobre nuestra Política de privacidad, no dude en contactarnos a <a href=\"mailto:contact@yopo.us\">contact@yopo.us</a></p>\r\n</div>');
INSERT INTO `pages` (`page_id`, `type`, `content`, `content_es`) VALUES
(2, '2', '<div class=\"body\" style=\"padding: 10px 15px;\">\r\n	<h3 class=\"section-heading\" style=\"text-align: center; font-size: 22px;\">TERMS AND CONDITIONS</h3>\r\n\r\n	<h4>TERMS OF USE</h4>\r\n	<p>By downloading, browsing, accessing or using this mobile application (“YoPO”), you agree to be bound by these Terms and Conditions of Use. We reserve the right to amend these terms and conditions at any time. If you disagree with any of these Terms and Conditions of Use, you must immediately discontinue your access to the Mobile Application and your use of the services offered on the Mobile Application. Continued use of the Mobile Application will constitute acceptance of these Terms and Conditions of Use, as may be amended from time to time.</p>\r\n\r\n	<h4>DEFINITIONS</h4>\r\n	<p>In these Terms and Conditions of Use, the following capitalised terms shall have the following meanings, except where the context otherwise requires: \"Account\" means an account created by a User on the Mobile Application as part of Registration. \"Privacy Policy\" means the privacy policy set out in Clause 14 of these Terms and Conditions of Use.\"Register\" means to create an Account on the Mobile Application and \"Registration\" means the act of creating such an Account. \"Services\" means all the services provided by YoPo  via the Mobile Application to Users, and \"Service\" means any one of them, \"Users\" means users of the Mobile Application, including you and \"User\" means any one of them.</p>\r\n\r\n	<h4>GENERAL ISSUES ON MOBILE APPLICATION AND SERVICES</h4>\r\n	<p>Applicability of the terms and conditions: the use of the Services and / or the Mobile Application are subject to these Terms and conditions of use.</p>\r\n	<p>Location: the mobile application, services and exchanges are intended exclusively for users who access the mobile application. We do not guarantee that the Services are available or are suitable for use, nor are we responsible for any unwanted activity as a product of the use of the app, if you access the Mobile Application, use the Services is responsible for the consequences and compliance with All applicable laws.</p>\r\n	<p>YoPo is not an employment agency for which we are not responsible for safeguarding a service provider while performing a service, nor is it safeguarding the user who hires a service, it is the responsibility of each party involved for their own security, we are only a platform for service connection.</p>\r\n	<p>Scope: the mobile application, the services are for your personal non-commercial use and should not be used for commercial purposes.</p>\r\n	<p>Prevention in use: We reserve the right to prevent you from using the Mobile Application and prevent misuse</p>\r\n	<p>Equipment and networks: the provision of the Services and the Mobile Application requires the use of a mobile phone necessary to access the Mobile Application, you will need Internet connectivity and adequate telecommunications links. You acknowledge that the terms of the agreement with your respective mobile network provider will remain in effect when you use the Mobile Application. As a result, the Mobile Service Provider may charge you for access to network connection services for the duration of the connection while accessing the Mobile Application or third-party charges that may arise. You accept responsibility for any charges that arise.</p>\r\n	<p>Permission to use the mobile application: If you are not the bill payer of the mobile phone or portable device that is used to access the mobile application, you will be assumed to have received the bill payer\'s permission to use the mobile application.</p>\r\n	<p>License to use the material: by sending any text or image (including photographs) (\"Material\") through the Application, you declare that you are the owner of the Material or that you have the proper authorization of the owner of the Material to use, reproduce and distribute it You hereby grant us a worldwide, royalty-free and non-exclusive license to use the Material to promote any product or service</p>\r\n\r\n	<h4>PAYMENTS AND COMMISSIONS</h4>\r\n	<p>Both the provider and the user are aware that they must pay a service fee according to the service performed, the quality may vary over time and that it will be known to both parties when contracting a service on the user\'s side or Offer a service on the seller side.</p>\r\n	<p>additionally on the provider side of your payment after discounted yopo commissions, the PayPal transfer commission will be deducted, which is the payment platform used by our application to send the money to the provider and recover the money from the user . These commissions are those listed by PayPal on their platform.</p>\r\n	<p>which is currently 2.9% of the value of the transfer plus 0.30 dollar if it is national 0 4.9% of the value of the transfer plus 0.30 dollar if it is international, these values may vary according to PayPal policies without this incurring responsibility for YoPo</p>\r\n\r\n	<h4>LOCATION ALERTS AND NOTIFICATIONS</h4>\r\n	<p>You agree to receive pre-programmed notifications (“Location Alerts”) on the Mobile Application from Merchants if you have turned on locational services on your mobile telephone or other handheld devices (as the case may be).</p>\r\n\r\n	<h4>YOUR OBLIGATIONS</h4>\r\n	<ul>\r\n		<li>User terms: You agree (and must comply with) the terms and conditions of the user, which may be modified from time to time.</li>\r\n		<li>Accurate information: You guarantee that all information provided in the Registry and contained as part of your Account is true, complete and accurate and that you will immediately inform us of any changes in said information by updating the information in your Account.</li>\r\n		<li>Content in the Mobile Application and the Service: It is your responsibility to ensure that any product or information available through the Mobile Application or the Services meets your specific requirements before performing any service.</li>\r\n		<li>Prohibitions regarding the use of the Services or the Mobile Application: without limitation, you agree not to use or allow another person to use the Services or the Mobile Application:</li>\r\n		<li>to send or receive any service that is not civil or in good taste</li>\r\n		<li>to send or receive any service that is threatening, seriously offensive, indecent, obscene or threatening, blasphemous or defamatory of any person, in contempt of court or in violation of trust, copyright, personality rights, publicity or privacy or any other third party rights;</li>\r\n		<li>to send or receive any service that encourages conduct that would be considered a criminal offense, would result in civil liability, or that would be contrary to the law or infringe the rights of a third party in any country in the world;</li>\r\n		<li>to send or receive any service that is technically harmful or</li>\r\n		<li>for a purpose other than that we design or intend to use;</li>\r\n		<li>for any fraudulent purpose;</li>\r\n		<li>apart from complying with accepted Internet practices and practices of any connected network;</li>\r\n		<li>in any way that is calculated to incite hatred against any ethnic, religious or other minority, or that is adversely calculated to affect any individual, group or entity; or</li>\r\n		<li>such that, or commits any act that imposes or imposes, an unreasonable or disproportionately large burden on our infrastructure.</li>\r\n		<li>Prohibitions in relation to the use of the Services, the Mobile Application: without limitation, you agree not to allow anyone else to:</li>\r\n	<li>resell any service;</li>\r\n		<li>provide false information, including false names, addresses and contact information, and fraudulently use credit / debit card numbers;</li>\r\n		<li></li>\r\n		<li>try to bypass our security or network, including access to data that is not intended for you, log in to a server or account that is not expressly authorized to access or test the security of other networks (such as running a port scan) ;</li>\r\n		<li>execute any form of network monitoring that will intercept data not intended for you;</li>\r\n		<li>engage in fraudulent interactions or transactions with us or with a Merchant (including the interaction or transaction allegedly on behalf of a third party in which you have no authority to bind that third party or if you intend to be a third party);</li>\r\n		<li>extract data or hack the mobile application;</li>\r\n		<li>use the Services or the Mobile Application in breach of these Terms and conditions of use;</li>\r\n		<li>participate in any illegal activity related to the use of the Mobile Application or the Services; or</li>\r\n		<li>Participate in any conduct that, in our reasonable and exclusive opinion, restricts or inhibits any other customer from using or properly enjoying the Application or Mobile Services</li>\r\n	</ul>\r\n\r\n	<h4>RULES ON THE USE OF THE SERVICE AND THE MOBILE APPLICATION</h4>\r\n		<p>We will make every reasonable effort to correct any errors or omissions as soon as possible after being notified of them. However, we do not guarantee that the Services or the Mobile Application are free from failures, and we accept no responsibility for such failures, errors or omissions. In case of any error, failure or omission, you must report it by contacting us at 18018880954.</p>\r\n		<p>We do not guarantee that your use of the Services or the Mobile Application will be uninterrupted and we do not guarantee that any information (or message) transmitted through the Services or the Mobile Application will be transmitted accurately, reliably, in a timely manner or there. Although we will attempt to allow uninterrupted access to the Services and the Mobile Application, access to the Services and the Mobile Application may be suspended, restricted or canceled at any time.</p>\r\n		<p>We do not guarantee that the Services and the Mobile Application are free of viruses or anything else that may have a harmful effect on any technology.</p>\r\n		<p>We reserve the right to change, modify, replace, suspend or delete without prior notice any information or Services in the Mobile Application from time to time. Your access to the Mobile Application and / or the Services may also be occasionally restricted to allow repairs, maintenance or the introduction of new facilities or services. We will attempt to restore such access as soon as reasonably possible. For the avoidance of doubt, we reserve the right to withdraw any information or Services from the Mobile Application at any time.</p>\r\n		<p>We reserve the right to block access and / or edit or delete any material that, in our reasonable opinion, may result in a violation of these Terms and Conditions of Use.</p>\r\n\r\n		<h4>SUSPENSION AND TERMINATION</h4>\r\n		<p>If you use (or any person other than you, with your permission, use) the Mobile Application, any Service in violation of these Terms and Conditions of Use, we may suspend your use of the Services and / or the Mobile Application.</p>\r\n		<p>If we suspend the Services or the Mobile Application, we may refuse to restore the Services or the Mobile Application for use until we receive a guarantee from you, in a manner we deem acceptable, that there will be no further breach of the provisions of these Terms and conditions of use.</p>\r\n		<p>Without limitation to anything else in this Clause 8, we shall be entitled immediately or at any time (in whole or in part) to: (a) suspend the Services and / or the Mobile Application; (b) suspend your use of the Services and / or the Mobile Application; and / or (c) suspend the use of the Services and / or the Mobile Application for people we believe are connected (in any way) to you, if:</p>\r\n		<p>you commit a breach of these Terms and conditions of use;</p>\r\n		<p>We suspect, for reasonable reasons, that you have committed or could commit a violation of these Terms and Conditions of Use; or We suspect, for reasonable reasons, that you may have committed or committed fraud against us or anyone. Our rights under this Clause 8 will not prejudge any other rights or remedies we may have with respect to any breach or any right, obligation or liability accrued prior to termination.</p>\r\n\r\n		<h4>DISCLAIMER AND EXCLUSION OF LIABILITY</h4>\r\n		<p>The Mobile Application, the Services, information about the Mobile Application and the use of all related facilities are provided \"as is, as available\" without any warranty, either express or implied.</p>\r\n		<p>To the fullest extent permitted by applicable law, we waive all representations and warranties related to the Mobile Application and its content, including in connection with any inaccuracies or omissions in the Mobile Application, merchantability warranties, quality, suitability for a particular purpose. , accuracy, availability, no infringement or implied warranties of the course of negotiation or use of trade.</p>\r\n		<p>We do not guarantee that the mobile application is always accessible, uninterrupted, timely, secure, error-free or free of computer viruses or other invasive or harmful code or that the mobile application will not be affected by any act of God or other force majeure events, including the inability to obtain or the shortage of necessary materials, equipment, energy or telecommunications facilities, lack of telecommunications equipment or facilities and failure of information technology or telecommunications equipment or facilities.</p>\r\n		<p>While we can make reasonable efforts to include accurate and up-to-date information in the Mobile Application, we do not guarantee or represent its accuracy, timeliness or integrity.</p>\r\n		<p>We will not be reliable for any act or omission of third parties, regardless of their cause, or for direct, indirect, incidental, special, consequential or punitive damages, regardless of their cause, as a result of the mobile application and the services offered in the mobile application , your access, use or inability to use the mobile application or the services offered in the mobile application, the dependence or download of the mobile application and / or services, or any delay, inaccuracy in the information or its transmission, including, among others, damages for loss of business or profits, use, data or other intangibles, even if we have been informed of the possibility of such damages.</p>\r\n		<p>We will not be reliable in the contract, tort (including negligence or breach of legal duty) or in any other way and whatever the cause thereof, for any indirect, consequential, collateral, special or incidental loss or damage suffered or incurred by you. in relation to the Mobile Application and these Terms and conditions of use. For the purposes of these Terms and Conditions of Use, the indirect or consequential loss or damage includes, without limitation, loss of income, profits, anticipated savings or business, loss of data or goodwill, loss of use or value of any equipment, including software, third party claims, and all associated and incidental costs and expenses.</p>\r\n		<p>The foregoing exclusions and limitations apply only to the extent permitted by law. None of your legal rights as a consumer that cannot be excluded or limited is affected.</p>\r\n\r\n		<p>Despite our efforts to ensure that our system is secure, you acknowledge that all electronic data transfers are potentially susceptible to being intercepted by others. We cannot and do not guarantee that data transfers in accordance with the Mobile Application or the email transmitted to and from us will not be monitored or read by others.</p>\r\n\r\n		<h4>INDEMNITY</h4>\r\n		<p>You agree to indemnify and keep us indemnified against any claim, action, lawsuit or proceeding filed or threatened to be filed against us that is caused by (a) your use of the Services, (b) the use of any other part of the Services you use your user ID, verification PIN and / or any identification number assigned by YoPo, and / or (c) your breach of any of these Terms and Conditions of Use, and to pay us damages, costs and interests in connection with such claim , action, demand or procedure.</p>\r\n\r\n		<h4>INTELLECTUAL PROPERTY RIGHTS</h4>\r\n		<p>All editorial content, information, photographs, illustrations, graphic material and other graphic materials, and names, logos and trademarks registered in the Mobile Application are protected by copyright laws and / or other international laws and / or treaties, and we they belong and / or our suppliers, as the case may be. These works, logos, graphics, sounds or images may not be copied, reproduced, retransmitted, distributed, disseminated, sold, published, transmitted or distributed, either in whole or in part, unless expressly authorized by us and / or our suppliers, as case can be.</p>\r\n		<p>Nothing contained in the Mobile Application must be construed as a concession, by implication, impediment or otherwise, of any license or right of use of any trademark displayed in the Mobile Application without our written permission. The improper use of any trademark or any other content displayed in the Mobile Application is prohibited.</p>\r\<p>We will not hesitate to take legal action against the unauthorized use of our trademarks, names or symbols to preserve and protect your rights in the matter. All rights not expressly granted in this document are reserved. Other product and company names mentioned here may also be trademarks of their respective owners.</p>\r\n\r\n	<h4>AMENDMENTS</h4>\r\n		<p>We may periodically make changes to the content of the Mobile Application, including descriptions and prices of the goods and services advertised, at any time and without prior notice. We assume no responsibility for errors or omissions in the content of the Mobile Application.</p>\r\n		<p>We reserve the right to modify these Terms and Conditions from time to time without prior notice. The revised Terms and conditions of use will be published in the Mobile Application and will become effective as of the date of said publication. It is recommended to review these terms and conditions periodically, as they are binding on you.</p>\r\n\r\n		<h4>APPLICABLE LAW AND JURISDICTION</h4>\r\n		<p>The mobile application can be accessed from all countries of the world where local technology allows. As each of these places has different laws, when accessing the Mobile Application, both you and we accept that the laws of the United States, without taking into account the conflicts of legal principles, will apply to all matters related to the use of the Mobile app.</p>\r\n		<p>You agree and accept that both you and we will submit to the exclusive jurisdiction of the US courts with respect to any dispute that arises or is related to these Terms and Conditions of Use.</p>\r\n\r\n		<h4>Privacy Policy</h4>\r\n		<p>Access to the Mobile Application and the use of the Services offered in the Mobile Application by YoPo is subject to this Privacy Policy. By accessing the Mobile Application and continuing to use the Services offered, you are deemed to have accepted this Privacy Policy and, in particular, your consent has been given for us to use and disclose your personal information in the manner prescribed in this Privacy Policy and for the purposes set forth in Clauses 3.7. and / or 4.1. We reserve the right to modify this Privacy Policy from time to time. If you do not agree with any part of this Privacy Policy, you must immediately terminate your access to the Mobile Application and your use of the Services.</p>\r\n\r\n		<p>As part of the normal operation of our Services, we collect, use and, in some cases, disclose your information to third parties. Consequently, we have developed this Privacy Policy so that you can understand how we collect, use, communicate, disclose and make use of your personal information when you use the Services in the Mobile Application:</p>\r\n\r\n		<ol>\r\n			<li>Before or at the time of collecting personal information, we will identify the purposes for which the information is collected.</li>\r\n			<li>We will collect and use personal information solely for the purpose of fulfilling the purposes specified by us and for other compatible purposes, unless we obtain the consent of the person in question or as required by law.</li>\r\n			<li>We will only retain personal information as long as necessary for the fulfillment of those purposes.</li>\r\n			<li>We will collect personal information by legal and fair means and, where appropriate, with the knowledge or consent of the person concerned.</li>\r\n			<li>Personal information must be relevant for the purposes for which it will be used and, to the extent necessary for those purposes, must be accurate, complete and up-to-date.</li>\r\n	<li>We will protect personal information through reasonable security measures against loss or theft, as well as unauthorized access, disclosure, copying, use or modification.</li>\r\n		</ol>\r\n		<p>We are committed to conducting our business in accordance with these principles to ensure that the confidentiality of personal information is protected and maintained.</p>\r\n\r\n	<!-------------------Englis pp--------------------->\r\n\r\n</div>', '<div class=\"body\" style=\"padding: 10px 15px;\">\r\n	<h3 class=\"section-heading\" style=\"text-align: center; font-size: 22px;\">TÉRMINOS Y CONDICIONES</h3>\r\n\r\n	<h4>TÉRMINOS DE USO</h4>\r\n	<p>Al descargar, navegar, acceder o utilizar esta aplicación móvil (\"YoPO\"), usted acepta estar sujeto a estos Términos y condiciones de uso. Nos reservamos el derecho de modificar estos términos y condiciones en cualquier momento. Si no está de acuerdo con alguno de estos Términos y condiciones de uso, debe suspender inmediatamente su acceso a la Aplicación móvil y su uso de los servicios ofrecidos en la Aplicación móvil. El uso continuado de la Aplicación móvil constituirá la aceptación de estos Términos y condiciones de uso, que pueden modificarse periódicamente.</p>\r\n\r\n	<h4>Definiciones</h4>\r\n	<p>En estos Términos y condiciones de uso, los siguientes términos en mayúscula tendrán los siguientes significados, excepto cuando el contexto requiera lo contrario: \"Cuenta\" significa una cuenta creada por un Usuario en la Aplicación móvil como parte del Registro. \"Política de privacidad\" significa la política de privacidad establecida en la Cláusula 14 de estos Términos y condiciones de uso. \"Registrarse\" significa crear una cuenta en la aplicación móvil y \"registro\" significa el acto de crear dicha cuenta. \"Servicios\" significa todos los servicios proporcionados por YoPo a través de la Aplicación Móvil a los Usuarios, y \"Servicio\" significa cualquiera de ellos, \"Usuarios\" significa usuarios de la Aplicación Móvil, incluido usted y \"Usuario\" significa cualquiera de ellos</p>\r\n\r\n	<h4>CUESTIONES GENERALES SOBRE LA APLICACIÓN MÓVIL Y LOS SERVICIOS</h4>\r\n	<p>Aplicabilidad de los términos y condiciones: el uso de los Servicios y / o la Aplicación móvil  están sujetos a estos Términos y condiciones de uso.</p>\r\n	<p>Ubicación: la aplicación móvil, los servicios y los canjes están destinados exclusivamente a usuarios que accedan a la aplicación móvil. No garantizamos que los Servicios estén disponibles o sean aptos para su uso , como tampoco nos responsabilizamos por cualquier actividad no deseada como producto del uso de la app, si accede a la Aplicación móvil, utiliza los Servicios  es responsable de las consecuencias y del cumplimiento de todas las leyes aplicables.</p>\r\n	<p>YoPo no es un agencia de empleos por lo cual no somos responsables de salvaguardar a un prestador de servicio mientras realiza un servicio como tampoco asi salvaguardar al usuario que contrata un servicio, es responsabilidad de cada parte involucrada su propia seguridad, solo somos una plataforma de coneccion de servicios.</p>\r\n	<p>Alcance: la aplicación móvil, los servicios son para su uso personal no comercial y no deben utilizarse con fines comerciales. Prevención en el uso: Nos reservamos el derecho de evitar que use la Aplicación móvil y evitar que realice un uso indebido\r\nEquipos y redes: la provisión de los Servicios y la Aplicación móvil requiere el uso de un teléfono móvil necesario para acceder a la Aplicación móvil, necesitará conectividad a Internet y enlaces de telecomunicaciones adecuados. Usted reconoce que los términos del acuerdo con su respectivo proveedor de red móvil continuarán vigentes cuando use la Aplicación Móvil. Como resultado, es posible que el Proveedor de servicios móviles le cobre por el acceso a los servicios de conexión de red durante el tiempo que dure la conexión mientras accede a la Aplicación móvil o a los cargos de terceros que puedan surgir. Usted acepta la responsabilidad de cualquier cargo que surja.\r\nPermiso para usar la aplicación móvil: si no es el pagador de facturas del teléfono móvil o dispositivo portátil que se utiliza para acceder a la aplicación móvil, se supondrá que recibió el permiso del pagador de facturas para usar la aplicación móvil.\r\nLicencia para usar el material: al enviar cualquier texto o imagen (incluidas fotografías) (\"Material\") a través de la Aplicación, usted declara que es el propietario del Material o que tiene la autorización adecuada del propietario del Material para usar, reproducir y distribuirlo Por la presente, nos otorga una licencia mundial, libre de regalías y no exclusiva para usar el Material para promocionar cualquier producto o servicio</p>\r\n\r\n<h4>PAGOS Y COMISIONES</h4>\r\n<p>Tanto el vendor como el user , estan concientes de que deben pagar una comision por servicio de acuerdo al servicio realizado la cual podra variar a traves del tiempo y que sera de conocimiento de ambas partes al momento de contratar un servicio en el lado del user o ofrecer un servicio en el lado del vendor.</p>\r\n<p>adicionalmente en el lado del vendor de su pago despues de descontadas las comisiones de yopo  se le descontara la comision de transferencia de PayPal  que es la plataforma de pagos que utilizara nuestra aplicacion para hacer llegar el dinero al vendor y recaudar el dinero por parte del user. estas comisiones son las listadas por paypal en su plataforma.\r\nque en este momento es 2.9 % del valor de la transferencia mas 0.30  dolar si es nacional 0 4.9 % del valor de la transferencia mas 0.30  dolar si es internacional, estos valores podran variar de acuerdo a las politicas de PayPal sin que esto contraiga responsabilidad para YoPo.</p>\r\n\r\n<h4>ALERTAS Y NOTIFICACIONES DE UBICACIÓN</h4>\r\n<p>Usted acepta recibir notificaciones preprogramadas (\"Alertas de ubicación\") en la Aplicación móvil de los Comerciantes si ha activado los servicios de ubicación en su teléfono móvil u otros dispositivos portátiles (según sea el caso).</p>\r\n\r\n<h4>TUS OBLIGACIONES</h4>\r\n\r\n<ul>\r\n	<li>Términos del usuario: Usted acepta (y deberá cumplir) los términos y condiciones del usuario , que pueden modificarse de vez en cuando.\r\n	Información precisa: Usted garantiza que toda la información proporcionada en el Registro y contenida como parte de su Cuenta es verdadera, completa y precisa y que nos informará de inmediato sobre cualquier cambio en dicha información actualizando la información en su Cuenta.\r\n	Contenido en la Aplicación móvil y el Servicio: es su responsabilidad asegurarse de que cualquier producto o información disponible a través de la Aplicación móvil o los Servicios cumpla con sus requisitos específicos antes de realizar cualquier servicio.\r\n	Prohibiciones en relación con el uso de los Servicios o la Aplicación móvil: sin limitación, usted se compromete a no usar ni permitir que otra persona use los Servicios o la Aplicación móvil</li>\r\<li>para enviar o recibir cualquier servicio que no sea civil o de buen gusto\r\n	para enviar o recibir cualquier servicio que sea amenazante, gravemente ofensivo, de carácter indecente, obsceno o amenazante, blasfemo o difamatorio de cualquier persona, en desacato a la corte o en violación de la confianza, derechos de autor, derechos de personalidad, publicidad o privacidad o cualquier otros derechos de terceros;</li>\r\n	<li>para enviar o recibir cualquier servicio que fomente conductas que se considerarían un delito penal, darían lugar a responsabilidad civil, o que serían contrarias a la ley o infringirían los derechos de un tercero en cualquier país del mundo;</li>\r\n	<li>para enviar o recibir cualquier servicio que sea técnicamente dañino o\r\n	para un propósito diferente al que los diseñamos o pretendemos que se usen;</li>\r\n	<li>para cualquier propósito fraudulento;</li>\r\n	<li>aparte de cumplir con las prácticas aceptadas de Internet y las prácticas de cualquier red conectada;\r\n	de cualquier manera que se calcule para incitar al odio contra cualquier minoría étnica, religiosa o de otro tipo, o que se calcule de manera adversa para afectar a cualquier individuo, grupo o entidad; o\r\n	de tal manera que, o cometa cualquier acto que imponga o imponga, una carga irrazonable o desproporcionadamente grande en nuestra infraestructura.</li>\r\n	<li>revender cualquier servicio;</li>\r\n	<li>proporcionar datos falsos, incluidos nombres, direcciones y datos de contacto falsos, y utilizar fraudulentamente números de tarjetas de crédito / débito;</li>\r\n	<li>intentar eludir nuestra seguridad o red, incluido el acceso a datos que no están destinados a usted, iniciar sesión en un servidor o cuenta a la que no está autorizado expresamente para acceder o probar la seguridad de otras redes (como ejecutar un escaneo de puertos);</li>\r\n	<li>ejecutar cualquier forma de monitoreo de red que interceptará datos no destinados a usted;</li>\r\n<li>entablar interacciones o transacciones fraudulentas con nosotros o con un Comerciante (incluida la interacción o transacción supuestamente en nombre de un tercero en el que no tiene autoridad para vincular a ese tercero o si pretende ser un tercero);</li>\r\n	<li>extraer datos o piratear la aplicación móvil;</li>\r\n	<li>usar los Servicios o la Aplicación móvil en incumplimiento de estos Términos y condiciones de uso;</li>\r\n	<li>participar en cualquier actividad ilegal relacionada con el uso de la Aplicación móvil o los Servicios; o</li>\r\n	<li>participar en cualquier conducta que, en nuestra opinión razonable y exclusiva, restrinja o inhiba a cualquier otro cliente de usar o disfrutar adecuadamente la Aplicación o Servicios Móviles</li>\r\n</ul>\r\n\r\n<h4>NORMAS SOBRE EL USO DEL SERVICIO Y LA APLICACIÓN MÓVIL</h4>\r\n<p>Haremos todos los esfuerzos razonables para corregir cualquier error u omisión tan pronto como sea posible después de ser notificado de ellos. Sin embargo, no garantizamos que los Servicios o la Aplicación móvil estén libres de fallas, y no aceptamos responsabilidad por tales fallas, errores u omisiones. En caso de cualquier error, falla u omisión, debe informarlo comunicándose con nosotros al 18018880954.</p>\r\n<p>No garantizamos que su uso de los Servicios o la Aplicación móvil será ininterrumpido y no garantizamos que cualquier información (o mensaje) transmitida a través de los Servicios o la Aplicación móvil se transmitirá de manera precisa, confiable, oportuna o al ahí. A pesar de que intentaremos permitir el acceso ininterrumpido a los Servicios y la Aplicación móvil, el acceso a los Servicios y la Aplicación móvil puede suspenderse, restringirse o cancelarse en cualquier momento.</p>\r\n<p>No garantizamos que los Servicios y la Aplicación móvil estén libres de virus o cualquier otra cosa que pueda tener un efecto nocivo en cualquier tecnología.</p>\r\n<p>Nos reservamos el derecho de cambiar, modificar, sustituir, suspender o eliminar sin previo aviso cualquier información o Servicios en la Aplicación móvil de vez en cuando. Su acceso a la Aplicación móvil y / o los Servicios también puede restringirse ocasionalmente para permitir reparaciones, mantenimiento o la introducción de nuevas instalaciones o servicios. Intentaremos restablecer dicho acceso tan pronto como sea razonablemente posible. Para evitar dudas, nos reservamos el derecho de retirar cualquier información o Servicios de la Aplicación móvil en cualquier momento.</p>\r\n<p>Nos reservamos el derecho de bloquear el acceso y / o editar o eliminar cualquier material que, en nuestra opinión razonable, pueda dar lugar a una violación de estos Términos y condiciones de uso.</p>\r\n\r\n<h4>SUSPENSIÓN Y TERMINACIÓN</h4>\r\n<p>Si usa (o cualquier otra persona que no sea usted, con su permiso, usa) la Aplicación móvil, cualquier Servicio en contravención de estos Términos y condiciones de uso, podemos suspender su uso de los Servicios y / o la Aplicación móvil.</p>\r\n<p>Si suspendemos los Servicios o la Aplicación móvil, podemos negarnos a restaurar los Servicios o la Aplicación móvil para su uso hasta que recibamos una garantía de su parte, en una forma que consideremos aceptable, de que no habrá más incumplimiento de las disposiciones de estos Términos y condiciones de uso.</p>\r\n<p>Sin limitación a cualquier otra cosa en esta Cláusula 8, tendremos derecho inmediatamente o en cualquier momento (en su totalidad o en parte) a: (a) suspender los Servicios y / o la Aplicación móvil; (b) suspender su uso de los Servicios y / o la Aplicación móvil; y / o (c) suspender el uso de los Servicios y / o la Aplicación móvil para personas que creemos que están conectadas (de cualquier manera) a usted, si:</p>\r\n<p>usted comete un incumplimiento de estos Términos y condiciones de uso;</p>\r\n<p>sospechamos, por razones razonables, que usted ha cometido o podría cometer una violación de estos Términos y condiciones de uso; o</p>\r\n<p>Sospechamos, por motivos razonables, que puede haber cometido o cometer un fraude contra nosotros o cualquier persona.</p>\r\n<p>Nuestros derechos bajo esta Cláusula 8 no prejuzgarán ningún otro derecho o recurso que podamos tener con respecto a cualquier incumplimiento o cualquier derecho, obligación o responsabilidad acumulados antes de la terminación.</p>\r\n\r\n<h4>RENUNCIA Y EXCLUSIÓN DE RESPONSABILIDAD</h4>\r\n<p>La Aplicación móvil, los Servicios, la información sobre la Aplicación móvil y el uso de todas las instalaciones relacionadas se proporcionan \"tal cual, según estén disponibles\" sin ninguna garantía, ya sea expresa o implícita.</p>\r\n<p>En la mayor medida permitida por la ley aplicable, renunciamos a todas las representaciones y garantías relacionadas con la Aplicación móvil y su contenido, incluso en relación con cualquier inexactitud u omisión en la Aplicación móvil, garantías de comerciabilidad, calidad, idoneidad para un propósito particular, precisión , disponibilidad, no infracción o garantías implícitas del curso de negociación o uso del comercio.</p>\r\n<p>No garantizamos que la aplicación móvil siempre sea accesible, ininterrumpida, oportuna, segura, libre de errores o libre de virus informáticos u otro código invasivo o dañino o que la aplicación móvil no se verá afectada por ningún acto de Dios u otra fuerza mayor eventos, incluida la incapacidad para obtener o la escasez de materiales necesarios, instalaciones de equipos, energía o telecomunicaciones, falta de equipos o instalaciones de telecomunicaciones y falla de la tecnología de la información o equipos o instalaciones de telecomunicaciones.</p>\r\n<p>Si bien podemos hacer esfuerzos razonables para incluir información precisa y actualizada en la Aplicación móvil, no garantizamos ni representamos su exactitud, oportunidad o integridad.</p>\r\n<p>No seremos confiables por ningún acto u omisión de terceros, independientemente de su causa, ni por daños directos, indirectos, incidentales, especiales, consecuentes o punitivos, independientemente de su causa, como resultado de la aplicación móvil y los servicios ofrecidos en la aplicación móvil, su acceso, uso o incapacidad para usar la aplicación móvil o los servicios ofrecidos en la aplicación móvil, la dependencia o la descarga de la aplicación móvil y / o servicios, o cualquier demora, inexactitud en la información o en su transmisión, incluidos, entre otros, daños por pérdida de negocios o ganancias, uso, datos u otros intangibles, incluso si se nos ha informado de la posibilidad de dichos daños.</p>\r\n<p>No seremos confiables en el contrato, agravio (incluyendo negligencia o incumplimiento del deber legal) o de cualquier otra forma y cualquiera que sea la causa del mismo, por cualquier pérdida o daño indirecto, consecuente, colateral, especial o incidental sufrido o incurrido por usted en relación con el Aplicación móvil y estos Términos y condiciones de uso. A los fines de estos Términos y condiciones de uso, la pérdida o daño indirecto o consecuente incluye, sin limitación, pérdida de ingresos, ganancias, ahorros anticipados o negocios, pérdida de datos o buena voluntad, pérdida de uso o valor de cualquier equipo, incluido el software, reclamos de terceros, y todos los costos y gastos asociados e incidentales.</p>\r\n\r\n<h4>INDEMNIDAD</h4>\r\n<p>Usted acepta indemnizar y mantenernos indemnizados contra cualquier reclamo, acción, demanda o procedimiento presentado o amenazado con ser presentado contra nosotros que sea causado por (a) su uso de los Servicios, (b) el uso de cualquier otra parte de los Servicios que utilizan su ID de usuario, PIN de verificación y / o cualquier número de identificación asignado por YoPo , y / o (c) su incumplimiento de cualquiera de estos Términos y Condiciones de Uso, y para pagarnos daños, costos e intereses en conexión con dicho reclamo, acción, demanda o procedimiento.</p>\r\n\r\n<h4>DERECHOS DE PROPIEDAD INTELECTUAL</h4>\r\n<p>Todo el contenido editorial, información, fotografías, ilustraciones, material gráfico y otros materiales gráficos, y nombres, logotipos y marcas registradas en la Aplicación móvil están protegidos por leyes de derechos de autor y / u otras leyes y / o tratados internacionales, y nos pertenecen y / o nuestros proveedores, según sea el caso. Estas obras, logotipos, gráficos, sonidos o imágenes no pueden copiarse, reproducirse, retransmitirse, distribuirse, difundirse, venderse, publicarse, transmitirse o distribuirse, ya sea total o parcialmente, a menos que lo autoricemos expresamente nosotros y / o nuestros proveedores, como caso puede ser.</p>\r\n<p>Nada de lo contenido en la Aplicación móvil debe interpretarse como una concesión, por implicación, impedimento o de otra manera, de cualquier licencia o derecho de uso de cualquier marca comercial que se muestre en la Aplicación móvil sin nuestro permiso por escrito. Se prohíbe el uso indebido de cualquier marca comercial o cualquier otro contenido que se muestre en la Aplicación móvil.</p>\r\n<p>No dudaremos en emprender acciones legales contra el uso no autorizado de nuestras marcas comerciales, nombres o símbolos para preservar y proteger sus derechos en el asunto. Todos los derechos no otorgados expresamente en este documento están reservados. Otros nombres de productos y compañías mencionados aquí también pueden ser marcas comerciales de sus respectivos propietarios.</p>\r\n<p>Las exclusiones y limitaciones anteriores se aplican solo en la medida permitida por la ley. Ninguno de sus derechos legales como consumidor que no puede ser excluido o limitado se ve afectado.</p>\r\n<p>A pesar de nuestros esfuerzos para garantizar que nuestro sistema sea seguro, usted reconoce que todas las transferencias electrónicas de datos son potencialmente susceptibles de ser interceptadas por otros. No podemos y no garantizamos que las transferencias de datos de conformidad con la Aplicación móvil o el correo electrónico transmitido hacia y desde nosotros no sean monitoreados o leídos por otros.</p>\r\n\r\n<h4>ENMIENDAS</h4>\r\n<p>Periódicamente podemos realizar cambios en el contenido de la Aplicación móvil, incluidas las descripciones y precios de los bienes y servicios anunciados, en cualquier momento y sin previo aviso. No asumimos ninguna responsabilidad por los errores u omisiones en el contenido de la Aplicación móvil.</p>\r\n<p>Nos reservamos el derecho de modificar estos Términos y condiciones de uso de vez en cuando sin previo aviso. Los Términos y condiciones de uso revisados ??se publicarán en la Aplicación móvil y entrarán en vigencia a partir de la fecha de dicha publicación. Se recomienda revisar estos términos y condiciones periódicamente, ya que son vinculantes para usted.</p>\r\n\r\n<h4>LEY APLICABLE Y JURISDICCIÓN</h4>\r\n<p>Se puede acceder a la aplicación móvil desde todos los países del mundo donde la tecnología local lo permita. Como cada uno de estos lugares tiene leyes diferentes, al acceder a la Aplicación móvil, tanto usted como nosotros aceptamos que las leyes de EEUU, sin tener en cuenta los conflictos de principios legales, se aplicarán a todos los asuntos relacionados con el uso de la Aplicación móvil.</p>\r\n<p>Usted acepta y acepta que tanto usted como nosotros nos someteremos a la jurisdicción exclusiva de los tribunales de EEUU con respecto a cualquier disputa que surja o esté relacionada con estos Términos y condiciones de uso.</p>\r\n\r\n<h4>Política de privacidad</h4>\r\n<p>El acceso a la Aplicación móvil y el uso de los Servicios ofrecidos en la Aplicación móvil por YoPo  está sujeto a esta Política de privacidad. Al acceder a la Aplicación móvil y al continuar utilizando los Servicios ofrecidos, se considera que ha aceptado esta Política de privacidad y, en particular, se ha dado su consentimiento para que usemos y divulguemos su información personal de la manera prescrita en esta Privacidad Política y para los fines establecidos en las Cláusulas 3.7. y / o 4.1. Nos reservamos el derecho de modificar esta Política de privacidad de vez en cuando. Si no está de acuerdo con alguna parte de esta Política de privacidad, debe interrumpir de inmediato su acceso a la Aplicación móvil y su uso de los Servicios.</p>\r\n\r\n<p>Como parte del funcionamiento normal de nuestros Servicios, recopilamos, usamos y, en algunos casos, divulgamos su información a terceros. En consecuencia, hemos desarrollado esta Política de privacidad para que pueda comprender cómo recopilamos, usamos, comunicamos, divulgamos y hacemos uso de su información personal cuando utiliza los Servicios en la Aplicación móvil:</p>\r\n\r\n<ol>\r\n	<li>Antes o en el momento de recopilar información personal, identificaremos los fines para los que se recopila la información.</li>\r\n	<li>Recopilaremos y utilizaremos información personal únicamente con el objetivo de cumplir con los fines especificados por nosotros y para otros fines compatibles, a menos que obtengamos el consentimiento de la persona en cuestión o según lo exija la ley.</li>\r\n	<li>Solo retendremos información personal el tiempo que sea necesario para el cumplimiento de esos propósitos.</li>\r\n	<li>Recopilaremos información personal por medios legales y justos y, cuando corresponda, con el conocimiento o consentimiento de la persona interesada.</li>\r\n	<li>La información personal debe ser relevante para los fines para los que se va a utilizar y, en la medida necesaria para esos fines, debe ser precisa, completa y actualizada.</li>\r\n	<li>Protegeremos la información personal mediante medidas de seguridad razonables contra pérdida o robo, así como acceso, divulgación, copia, uso o modificación no autorizados.</li>\r\n</ol>\r\n<p>Estamos comprometidos a llevar a cabo nuestro negocio de acuerdo con estos principios para asegurar que la confidencialidad de la información personal esté protegida y mantenida.</p>\r\n</div>');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reserve_seats`
--

CREATE TABLE `reserve_seats` (
  `reserve_seat_id` int(11) UNSIGNED NOT NULL,
  `reservation_id` varchar(225) DEFAULT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `seat_id` int(11) UNSIGNED NOT NULL,
  `office_id` int(11) UNSIGNED NOT NULL,
  `seat_no` varchar(225) DEFAULT NULL,
  `advance_days` int(11) DEFAULT NULL,
  `reserve_date` date DEFAULT NULL,
  `status` enum('0','1','2','3','4') NOT NULL DEFAULT '0' COMMENT '0=pending,1=accpted,2=rejected by admin,3=rejected by user,4=auto accpted',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `seat_id` int(10) UNSIGNED NOT NULL,
  `office_id` int(10) UNSIGNED NOT NULL,
  `seat_no` varchar(225) NOT NULL,
  `description` text,
  `booking_mode` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1=manual,2=auto_accpted',
  `seat_type` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1=unblock,2=blocked',
  `is_show_user_details` enum('0','1') DEFAULT '0',
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role` enum('1','2','3') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1=admin,2=users,3=driver',
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `job_profile` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verify_status` enum('0','1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_image` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lng` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_verify_status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '0=otp_not_verified,1=otp_verified',
  `active_status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '0=deactivate,1 activate',
  `approve_status` enum('0','1','2') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '0=pending,1=approved,2=rejected',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notification_status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '0=off,1=on',
  `device_type` enum('android','ios','web','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_token` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language` enum('en','es') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `logo_image` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role`, `user_name`, `first_name`, `last_name`, `job_profile`, `email`, `email_verify_status`, `phone_number`, `profile_image`, `address`, `lat`, `lng`, `otp`, `otp_verify_status`, `active_status`, `approve_status`, `password`, `remember_token`, `notification_status`, `device_type`, `device_token`, `language`, `logo_image`, `created_at`, `updated_at`, `deleted_at`)
VALUES(1, '1', 'Paul Foran', 'Paul', 'Foran', NULL, 'paul@datagov.ai', '1', '086 0449410', NULL, NULL, '22.719568', '75.857727', NULL, '0', '1', '1', '$2y$10$TL6Pl8v2TH2QYW48TYMEquTfAvjqQsGOv4RjXx1fQeNAjSFbbRF66', NULL, '1', 'web', 'mamama', 'en', 'NDheCwpEWB_1600523500.jpg', '2020-02-09 18:30:00', '2020-09-19 07:51:40', NULL);
--
-- Indexes for dumped tables
--

--
-- Indexes for table `buildings`
--
ALTER TABLE `buildings`
  ADD PRIMARY KEY (`building_id`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`chat_id`);

--
-- Indexes for table `delete_users`
--
ALTER TABLE `delete_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `offices`
--
ALTER TABLE `offices`
  ADD PRIMARY KEY (`office_id`);

--
-- Indexes for table `office_images`
--
ALTER TABLE `office_images`
  ADD PRIMARY KEY (`office_image_id`);

--
-- Indexes for table `office_seats`
--
ALTER TABLE `office_seats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`page_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`(191));

--
-- Indexes for table `reserve_seats`
--
ALTER TABLE `reserve_seats`
  ADD PRIMARY KEY (`reserve_seat_id`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`seat_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buildings`
--
ALTER TABLE `buildings`
  MODIFY `building_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `chat_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `delete_users`
--
ALTER TABLE `delete_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `offices`
--
ALTER TABLE `offices`
  MODIFY `office_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `office_images`
--
ALTER TABLE `office_images`
  MODIFY `office_image_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `office_seats`
--
ALTER TABLE `office_seats`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `page_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `reserve_seats`
--
ALTER TABLE `reserve_seats`
  MODIFY `reserve_seat_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `seat_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
