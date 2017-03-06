<?php



return array(



    /*

    |--------------------------------------------------------------------------

    | Validation Language Lines

    |--------------------------------------------------------------------------

    |

    | The following language lines contain the default error messages used by

    | the validator class. Some of these rules have multiple versions such

    | as the size rules. Feel free to tweak each of these messages here.

    |

    */



    "accepted" => ":attribute mora biti prihvaćen.",

    "active_url" => ":attribute nije validna adresa.",

    "after" => ":attribute mora biti datum poslije :date.",

    "alpha" => ":attribute može sadržavati samo slova.",

    "alpha_dash" => ":attribute može sadržavati samo slova, brojeve, i crtice.",

    "alpha_num" => ":attribute može sadržavati samo slova i brojeve.",

    "array" => ":attribute mora biti polje.",

    "before" => ":attribute mora biti datum prije :date.",

    "between" => array(

        "numeric" => ":attribute mora biti između :min i :max.",

        "file" => ":attribute mora biti između :min i :max kilobytes.",

        "string" => ":attribute mora biti između :min and :max znakova.",

        "array" => ":attribute mora biti između :min i :max članova.",

    ),

    "boolean"              => ":attribute polje mora biti istina ili laž",

    "confirmed"            => ":attribute potvrda ne odgovara.",

    "date"                 => ":attribute nije važeći datum.",

    "date_format"          => ":attribute does not match the format :format.",

    "different"            => ":attribute i :other moraju biti različiti.",

    "digits"               => ":attribute mora biti :digits znamenaka.",

    "digits_between"       => ":attribute mora biti između :min i :max znamenaka.",

    "email"                => ":attribute mora biti validna email adresa.",

    "exists"               => "Označeni :attribute nije validan.",

    "image"                => ":attribute mora biti slika.",

    "in"                   => "Označeni :attribute nije validan.",

    "integer"              => ":attribute mora biti cijeli broj.",

    "ip"                   => ":attribute mora biti ispravna IP adresa.",

    "max"                  => array(

        "numeric" => ":attribute ne može biti veći od :max.",

        "file"    => ":attribute ne može biti veći od :max kilobytes.",

        "string"  => ":attribute ne može biti veći od :max znakova.",

        "array"   => ":attribute ne može imati više od :max članova.",

    ),

    "mimes"                => "The :attribute mora biti datoteka tipa :values.",

    "min"                  => array(

        "numeric" => ":attribute mora biti najmanje :min.",

        "file"    => ":attribute mora biti najmanje :min kilobytes.",

        "string"  => ":attribute mora biti najmanje :min znakova.",

        "array"   => ":attribute mora imati najmanje :min članova.",

    ),

    "not_in"               => "Označeni :attribute nije validan.",

    "numeric"              => ":attribute mora biti broj.",

    "regex"                => ":attribute format je neispravan.",

    "required"             => ":attribute polje je obavezno.",

    "required_if"          => ":attribute polje je obavezno kada je :other :value.",

    "required_with"        => ":attribute polje je obavezno kada je :values prisutan.",

    "required_with_all"    => ":attribute polje je obavezno kada je :values prisutan.",

    "required_without"     => ":attribute polje je obavezno kada :values nije prisutan.",

    "required_without_all" => ":attribute polje je obavezno kada niti jedan od :values nije prisutan.",

    "same"                 => ":attribute i :other moraju biti jednaki.",

    "size"                 => array(

        "numeric" => ":attribute mora biti :size.",

        "file"    => ":attribute mora biti :size kilobytes.",

        "string"  => ":attribute mora biti :size znakova.",

        "array"   => ":attribute mora sadržavati :size članova.",

    ),

    "unique"               => ":attribute se već koristi.",

    "url"                  => ":attribute format je neispravan.",

    "decimal"              => ":attribute mora biti prirodni ili decimalni broj sa .",



    /*

    |--------------------------------------------------------------------------

    | Custom Validation Language Lines

    |--------------------------------------------------------------------------

    |

    | Here you may specify custom validation messages for attributes using the

    | convention "attribute.rule" to name the lines. This makes it quick to

    | specify a specific custom language line for a given attribute rule.

    |

    */



    'custom' => array(

        'attribute-name' => array(

            'rule-name' => 'custom-message',

        ),

    ),



    /*

    |--------------------------------------------------------------------------

    | Custom Validation Attributes

    |--------------------------------------------------------------------------

    |

    | The following language lines are used to swap attribute place-holders

    | with something more reader friendly such as E-Mail Address instead

    | of "email". This simply helps us make messages a little cleaner.

    |

    */



    'attributes' => array('password' => 'Lozinka', 'password_confirmation' => 'Potvrda lozinke', 'email' => 'E-mail',

                          'first_name' => 'Ime', 'last_name' => 'Prezime', 'company_name' => 'Naziv poduzeća',

                          'client_type' => 'Tip klijenta', 'name' => 'Naziv', 'oib' => 'OIB', 'tax_number' => 'Porezni broj',

                          'address' => 'Adresa', 'city' => 'Grad', 'country' => 'Država', 'zipcode' => 'Poštanski broj',

                          'phone' => 'Telefon', 'category' => 'Kategorija', 'code' => 'Šifra', 'unit' => 'Jedinica mjere',

                          'price' => 'Cijena', 'tax_group' => 'Grupa poreza', 'service' => 'Tip', 'currency' => 'Valuta',

                          'bank_account' => 'Žiro račun', 'office_number' => 'Broj poslovnog prostora',

                          'dom_register_number' => 'Broj blagajne za domaće klijente',

                          'int_register_number' => 'Broj blagajne za strane klijente',

                          'int_notes' => 'Međunarodna napomena za ponude/račune', 'iban' => 'IBAN', 'swift' => 'SWIFT',

                          'document_footer' => 'Podnožje ponuda/računa', 'language' => 'Jezik', 'time_zone' => 'Vremenska zona'),



);

