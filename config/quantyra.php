<?php

return [
    'site' => [
        'language' => 'en',
        'title' => 'Quantyra Labs LLC',
        'description' => 'Quantyra Labs builds SaaS products and software infrastructure for businesses across North America. Florida-based technology company.',
    ],

    'navigation' => [
        'logo' => 'Quantyra Labs',
        'logo_src' => '/logos/QuantyraLabs-Logo-Transparent.png',
        'links' => [
            ['label' => 'Home', 'href' => '/', 'route' => 'home'],
            ['label' => 'About', 'href' => '/about', 'route' => 'about'],
            ['label' => 'Legal', 'href' => '/legal', 'route' => 'legal'],
        ],
        'contact_label' => 'Contact',
        'contact_href' => '/contact',
        'contact_route' => 'contact',
    ],

    'hero' => [
        'headline' => 'SaaS products built for scale',
        'subtext' => 'Quantyra Labs is a Florida-based product company. We design, ship, and operate SaaS solutions and supporting infrastructure for teams across North America.',
        'cta_primary' => 'Contact us',
        'cta_primary_href' => '/contact',
        'cta_secondary' => 'About the company',
        'cta_secondary_href' => '/about',
    ],

    'home_products' => [
        'label' => 'What we build',
        'heading' => 'Product lines & platforms',
        'description' => 'Focused SaaS categories where reliability, security, and compliance matter from day one.',
        'items' => [
            [
                'title' => 'Customer operations',
                'description' => 'Workflow and engagement tools that help teams deliver consistent service as they grow.',
            ],
            [
                'title' => 'Security & compliance',
                'description' => 'Controls, auditability, and guardrails designed for regulated and high-trust environments.',
            ],
            [
                'title' => 'Business automation',
                'description' => 'Connectors and automation layers that reduce manual work across your stack.',
            ],
        ],
    ],

    'about' => [
        'label' => 'About us',
        'heading' => 'A product company for serious operators',
        'description' => 'Quantyra Labs is a Florida-based technology company that designs, builds, and operates SaaS products. We serve businesses across North America with a focus on reliability, security, and measurable outcomes.',
        'mission' => [
            'heading' => 'Our approach',
            'text' => 'We ship products that solve real operational problems. Our teams pair product thinking with strong engineering so customers get software that is dependable, secure, and straightforward to adopt.',
        ],
        'values' => [
            [
                'title' => 'Reliability',
                'description' => 'We prioritize uptime, predictable performance, and clear operational practices.',
            ],
            [
                'title' => 'Security',
                'description' => 'Security is part of design and delivery—not an afterthought bolted on at the end.',
            ],
            [
                'title' => 'Compliance',
                'description' => 'We align with industry expectations and help customers meet their obligations.',
            ],
            [
                'title' => 'Support',
                'description' => 'We provide responsive support and documentation so teams can move with confidence.',
            ],
        ],
        'location' => [
            'heading' => 'Headquarters',
            'address' => 'St. Petersburg, Florida',
            'detail' => 'Operating across North America',
        ],
    ],

    'legal' => [
        'label' => 'Legal',
        'heading' => 'Terms of Service & Privacy Policy',
        'last_updated' => 'March 19, 2026',
        'sections' => [
            [
                'id' => 'terms',
                'title' => 'Terms of Service',
                'content' => <<<'MARKDOWN'
## 1. Acceptance of Terms

By accessing or using any Quantyra Labs service, you agree to be bound by these Terms of Service.

## 2. Description of Services

Quantyra Labs provides software infrastructure and related technology services.

## 3. Account Registration

You must provide accurate and complete information when creating an account. You are responsible for maintaining the security of your account credentials.

## 4. Acceptable Use

You agree not to use our services for any unlawful purpose or in any way that could damage our infrastructure. Prohibited activities include attempting to gain unauthorized access and transmitting malware.

## 5. Payment and Billing

Fees are billed in advance as specified in your service agreement. All fees are non-refundable except as required by law.

## 6. Service Level Agreements

Specific terms and support response times are outlined in individual service agreements.

## 7. Intellectual Property

All intellectual property rights in our services remain the property of Quantyra Labs or its licensors.

## 8. Limitation of Liability

To the maximum extent permitted by law, Quantyra Labs shall not be liable for any indirect, incidental, or consequential damages.

## 9. Termination

We may suspend or terminate your access for violation of these terms. You may cancel your subscription with 30 days notice.

## 10. Governing Law

These terms shall be governed by the laws of the State of Florida.

## 11. Changes to Terms

We reserve the right to modify these terms at any time. Continued use constitutes acceptance of modified terms.
MARKDOWN
            ],
            [
                'id' => 'privacy',
                'title' => 'Privacy Policy',
                'content' => <<<'MARKDOWN'
## 1. Information We Collect

We collect information you provide directly to us, including account information, billing details, usage data, and technical data.

## 2. How We Use Your Information

We use the information we collect to provide and improve our services, process transactions, send technical notices, and respond to inquiries.

## 3. Data Security

We implement appropriate technical and organizational measures to protect your data, including encryption, access controls, and security audits.

## 4. Data Retention

We retain your information as long as your account is active or as needed to provide services. We will delete data upon request subject to legal obligations.

## 5. Third-Party Services

We may use third-party service providers who have access to your information only to perform specific tasks on our behalf.

## 6. Your Rights

You may have the right to access, correct, delete, or request portability of your personal information depending on your location.

## 7. Cookies and Tracking

We use cookies to enhance your experience and analyze usage. You can control cookies through your browser settings.

## 8. International Transfers

Your information may be transferred to and processed in the United States. We ensure appropriate safeguards are in place.

## 9. Children's Privacy

Our services are not directed to individuals under 16. We do not knowingly collect personal information from children.

## 10. Changes to This Policy

We may update this privacy policy from time to time. We will notify you of changes by posting the new policy.

## 11. Contact Us

For questions about this privacy policy, contact us at privacy@quantyra.io.
MARKDOWN
            ],
        ],
    ],

    'contact' => [
        'label' => 'Contact',
        'heading' => 'Contact us',
        'description' => 'For inquiries, partnerships, or product questions, reach out to our team.',
        'address' => [
            'line1' => 'Quantyra Labs LLC',
            'line2' => 'St. Petersburg, Florida',
            'line3' => 'United States',
        ],
        'form' => [
            'name_label' => 'Name',
            'email_label' => 'Email',
            'department_label' => 'Department',
            'departments' => [
                ['value' => 'general', 'label' => 'General inquiries'],
                ['value' => 'sales', 'label' => 'Sales'],
                ['value' => 'support', 'label' => 'Support'],
            ],
            'company_label' => 'Company (optional)',
            'message_label' => 'Message',
            'submit_label' => 'Send message',
            'success_message' => 'Thank you for your message. We will respond within 24–48 hours.',
            'form_heading' => 'Send us a message',
        ],
    ],

    'footer' => [
        'logo' => 'Quantyra Labs',
        'description' => 'SaaS products and software infrastructure. Based in Florida, operating across North America.',
        'columns' => [
            [
                'title' => 'Company',
                'links' => [
                    ['label' => 'About', 'href' => '/about', 'route' => 'about'],
                    ['label' => 'Contact', 'href' => '/contact', 'route' => 'contact'],
                ],
            ],
            [
                'title' => 'Legal',
                'links' => [
                    ['label' => 'Terms of Service', 'href' => '/legal', 'route' => 'legal', 'tab' => 'terms'],
                    ['label' => 'Privacy Policy', 'href' => '/legal', 'route' => 'legal', 'tab' => 'privacy'],
                ],
            ],
        ],
        'copyright' => 'Quantyra Labs LLC. All rights reserved.',
    ],

    'mail' => [
        'contact_notification' => env('CONTACT_NOTIFICATION_EMAIL'),
    ],
];
