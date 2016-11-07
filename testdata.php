<?php

//
// Used to initialize the Database and fill some Values into it
//

//Delete all tables if Request is clear_tables, for test purpuses
// if you go live and have stable data, delete this part.
  $db = Core::instance()->getDb();
  //if(isset($_REQUEST['clear_tables']) && $_REQUEST['clear_tables'] == true) {
    $db->drop('user');
    $db->drop('book');
    $db->drop('order');
    $db->drop('position');
    $db->drop('genre');
    $db->drop('bookgenre');
    $db->drop('type');
 // }

  TypeModel::create(array(
      'key' => 'Taschenbuch2',
      'name' => 'Taschenbuch2'
    ));

//Check if there is the user Table, if not, create some entries
  if(!$db->tableExists('user')) {
    UserModel::create(array(
      'user_name' => 'tony',
      'password' => Utilities::hash('12345', USER_SALT),
      'first_name' => 'Tony',
      'last_name' => 'Stark',
      'lang' => 'de',
      'isAdmin' => false,
      'streetname' => 'Schwalbenweg',
      'streetnumber' => '10a',
      'zip' => 3012,
      'city' => 'Bern',
      'state' => 'Schweiz',
      'email' => 'marcel.tschanz@bluemail.ch'
    ));

    UserModel::create(array(
      'user_name' => 'hulk',
      'password' => Utilities::hash('12345', USER_SALT),
      'first_name' => 'Bruce',
      'last_name' => 'Banner',
      'lang' => 'de',
      'isAdmin' => true,
      'streetname' => 'Tiefenaugasse',
      'streetnumber' => '17',
      'zip' => 3012,
      'city' => 'Bern',
      'state' => 'Schweiz',
      'email' => 'marcel.tschanz@bluemail.ch'
    ));
    UserModel::create(array(
      'user_name' => 'thor',
      'password' => Utilities::hash('12345', USER_SALT),
      'first_name' => 'Thor',
      'last_name' => 'Odinsson',
      'lang' => 'de',
      'isAdmin' => false,
      'streetname' => 'Molkereiweg',
      'streetnumber' => '31',
      'zip' => 3217,
      'city' => 'Thun',
      'state' => 'Schweiz',
      'email' => 'marcel.tschanz@bluemail.ch'
    ));
    UserModel::create(array(
      'user_name' => 'max',
      'password' => Utilities::hash('12345', USER_SALT),
      'first_name' => 'Max',
      'last_name' => 'Muster',
      'lang' => 'en',
      'isAdmin' => true,
      'streetname' => 'Ahornweg',
      'streetnumber' => '4',
      'zip' => 4212,
      'city' => 'New York',
      'state' => 'USA',
      'email' => 'marcel.tschanz@bluemail.ch'
    ));

  }

  //Check if there is the book Table, if not, create some entries
  if(!$db->tableExists('book')) {

  BookModel::create(array(
    'name' => "Angel Of The Dark",
    'isbn' => "0062073451",
    'cover' => "theme/images/books/0062073451.jpg",
    'title' => "Angel Of The Dark",
    'author' => "Sidney Sheldon",
    'year_of_publication' => 2012,
    'price' => 11.10,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "EN",
    'description' => "As the future prima, or head witch of her clan, Angela McAllister is expected to bond with her consort during her twenty-first year, thus ensuring that she will come into her full powers at the appointed time. The clock is ticking down, and her consort has yet to make an appearance. Instead, her dreams are haunted by a man she's never seen, the one she believes must be her intended match.

But with time running out, and dark forces attempting to seize her powers for their own, Angela is faced with a terrible choice: give up her dreams of the man she may never meet and take the safer path, or risk leaving her clan and everyone in it at the mercy of those who seek their ruin.

Darkangel is the first book in the Witches of Cleopatra Hill, a paranormal romance series set in the haunted town of Jerome, Arizona.",
    'original_language' => "EN",
    'number_of_pages' => 400,
    'version' => 1,
    'type' => "Taschenbuch",
    'genre' => "Krimi"
  ));

BookModel::create(array(
    'name' => "Max fährt mit",
    'isbn' => "3197495950",
    'cover' => "theme/images/books/3197495950.jpg",
    'title' => "Max fährt mit",
    'author' => "Ulrike Fischer",
    'year_of_publication' => 2014,
    'price' => 9.00,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "DE",
    'description' => "Zielgruppe: Kinder ab 2 Jahren Auf dem Bauernhof gibt es viele Fahrzeuge. Doch wie staunt Max erst, als er in die Stadt kommt! Max fährt mit ist der sechste Titel in der Reihe bilibrini – die kleinen Zweisprachigen und widmet sich in kurzen, einfachen Sätzen dem Thema Fahrzeuge. Die farbenfrohen Illustrationen laden Kinder ab 2 Jahren zum Betrachten ein. Die Wort-Bild-Leiste auf jeder Seite greift wichtige Gegenstände erneut auf und ermöglicht eine aktive Förderung des Wortschatzes schon für die Kleinsten.",
    'original_language' => "DE",
    'number_of_pages' => 16,
    'version' => 1,
    'type' => "Hardcover Buch",
    'genre' => "Kinderbuch"
  ));

BookModel::create(array(
    'name' => "Wind",
    'isbn' => "3453410831",
    'cover' => "theme/images/books/3453410831.jpg",
    'title' => "Wind",
    'author' => "Stephen King",
    'year_of_publication' => 2013,
    'price' => 24.00,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "DE",
    'description' => "Ein Sturm zieht auf


Roland Deschain, der letzte Revolvermann, und seine Gefährten haben den Grünen Palast hinter sich gelassen. Als sie auf dem Pfad des Balkens ins Land Donnerschlag unterwegs sind, zieht ein heftiger Sturm herauf, und sie finden Schutz in einer verlassenen Hütte. Dort erzählt Roland seinen Begleitern, was in seiner Jugend geschah, nachdem er unbeabsichtigt seine Mutter umgebracht hatte: Sein Vater schickte ihn zu einer entlegenen Ranch, wo grausame Morde stattfanden. Alle Anzeichen deuteten auf einen Gestaltwandler als Täter hin, und es gab nur einen Zeugen – einen kleinen Jungen, der jetzt seines Lebens nicht mehr sicher war.


Mit Wind legt Stephen King einen achten Roman seines großen Endzeitepos um den Dunklen Turm vor, bei dem es sich nach eigenem Bekunden um sein wichtigstes Werk handelt. Derzeit befindet sich eine Verfilmung des gesamten Zyklus in der Vorproduktion.",
    'original_language' => "EN",
    'number_of_pages' => 432,
    'version' => 1,
    'type' => "Hardcover Buch",
    'genre' => "Fantasy"
  ));

BookModel::create(array(
    'name' => "Ein perfekter Freund",
    'isbn' => "3257233787",
    'cover' => "theme/images/books/3257233787.jpg",
    'title' => "Ein perfekter Freund",
    'author' => "Martin Suter
",
    'year_of_publication' => 2003,
    'price' => 15.99,
    'currency' => "CHF",
    'available' => "Vergriffen",
    'language' => "DE",
    'description' => "Welch ein Erwachen! Kaum Gefühl im Gesicht, keine Ahnung, wo man ist. Und die Frau, die sich so rührend um einen kümmert, soll die eigene Freundin sein? Der Journalist Fabio Rossi hat einen Gedächtnisverlust erlitten. Irgendjemand hat ihm kräftig auf den Schädel gehauen. 50 Tage fehlen ihm seitdem. Und in diesen paar Tagen habe er sein Leben komplett umgekrempelt, habe Wohnung und Partnerin gewechselt, sei überhaupt nicht mehr der, der er einst wahr. Und das nicht zu seinem Vorteil. Als Journalist begibt er sich nun auf Recherche in eigener Sache und rekonstruiert die letzten Tage vor dem Black-out.
Martin Suter hat die Geschichte raffiniert konstruiert und taucht die Leser immer wieder in widersprüchliche Gefühle. Ist Rossi nun wirklich der moralisch überlegene Journalist, hat ihm sein Kumpel den Scoop um BSE in Schokostängeln geklaut? Warum ist die frisch gebackene Witwe des Forschers so glücklich -- und plötzlich auch finanziell gut gepolstert? Rossi stand -- und steht nun wieder -- kurz vor der Enthüllung eines riesigen Skandals, als er die Macht der Lebensmittelkonzerne zu spüren bekommt. Ihr Angebot ist eines, das man nicht ablehnen sollte.

Martin Suters Romane sind fehlerlos. Die Sprache ist präzis, manchmal von einer fast unheimlichen Trockenheit. Und vor allem ist Ein perfekter Freund höchst spannend -- und nebenbei auch noch lehrreich. Dass der Humor bei Suter nicht zu kurz kommt, vor allem der schwarze, kennt man. Und auch da liefert er höchst amüsante Müsterchen, die beispielsweise die Techniken des Gedächtnistrainings betreffen. So in dem Stil, wenn die Marlen plötzlich Lilli heißt.",
    'original_language' => "DE",
    'number_of_pages' => 352,
    'version' => 1,
    'type' => "Taschenbuch",
    'genre' => "Roman"
  ));

BookModel::create(array(
    'name' => "Thor: God of Thunder",
    'isbn' => "785168427",
    'cover' => "theme/images/books/785168427.jpg",
    'title' => "Thor: God of Thunder",
    'author' => "Marvel
",
    'year_of_publication' => 2013,
    'price' => 19.99,
    'currency' => "CHF",
    'available' => "Vergriffen",
    'language' => "DE",
    'description' => "Der letzte Kampf der Götter. Irgendwo am Ende der Zeit werden alle Götter des Universums als Sklaven gehalten. Dort werden sie gezwungen, eine Maschine zu errichten, die das Antlitz der Schöpfung für immer verändern wird. Was ist der wahre Sinn und Zweck der ... Götterbombe? Und was kann Thor, der letzte freie Gott im Kosmos, tun, um sie und Gorr, den Gottesschlächter, aufzuhalten? Das spektakuläre Finale der Gorr-Saga mit den US-Ausgaben Thor- God of Thunder 7-11 von Jason Aaron und Esad Ribic. Erstmals dabei: Die Mädchen des Donners!",
    'original_language' => "EN",
    'number_of_pages' => 136,
    'version' => 1,
    'type' => "Taschenbuch",
    'genre' => "Comic"
  ));

BookModel::create(array(
    'name' => "My Travel Journal: Mein Reisetagebuch",
    'isbn' => "1445486857",
    'cover' => "theme/images/books/1445486857.jpg",
    'title' => "My Travel Journal: Mein Reisetagebuch",
    'author' => "Parragon",
    'year_of_publication' => 2012,
    'price' => 7.00,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "DE",
    'description' => "Wunderschönes Design im Retro-Stil Für Ihre Listen, Tagespläne und Gedanken Zum Einkleben von Tickets, Fotos, Stadtplänen und Visitenkarten Mit einer Tasche für Erinnerungsstücke Im praktischen Taschenformat, perfekt zum Mitnehmen Auffallende Covergestaltung auf Dekokarton mit nostalgischem Flair",
    'original_language' => "DE",
    'number_of_pages' => 144,
    'version' => 1,
    'type' => "Taschenbuch",
    'genre' => "Reisen"
  ));

BookModel::create(array(
    'name' => "Pazifik - Ozean der Zukunft",
    'isbn' => "3782210174",
    'cover' => "theme/images/books/3782210174.jpg",
    'title' => "Pazifik - Ozean der Zukunft",
    'author' => "Joachim Feyerabend",
    'year_of_publication' => 2010,
    'price' => 11.00,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "DE",
    'description' => "Der Pazifische Ozean ist das mit Abstand größte Weltmeer und bedeckt über ein Drittel der Erdoberfläche. Eingerahmt von einem gewaltigen Vulkangürtel - dem Ring of Fire - war er in der Vergangenheit Tummelplatz von Entdeckern und Eroberern und beflügelte unter dem Begriff Südsee die Phantasien von Seeleuten und Dichtern. Heute rückt der Pazifik mit seinen zahlreichen Randmeeren immer mehr in den Blickpunkt der Weltöffentlichkeit, politisch, ökonomisch und klimatisch. Der Autor stellt in seinem Buch dieses Weltmeer von seiner erdgeschichtlichen Entstehung bis hin zu den Folgen des fortschreitenden Klimawandels vor.",
    'original_language' => "DE",
    'number_of_pages' => 208,
    'version' => 1,
    'type' => "Taschenbuch",
    'genre' => "Geografie"
  ));

BookModel::create(array(
    'name' => "Geschichte der Alchemie",
    'isbn' => "3406616011",
    'cover' => "theme/images/books/3406616011.jpg",
    'title' => "Geschichte der Alchemie",
    'author' => "Claus Priesner",
    'year_of_publication' => 2011,
    'price' => 10.00,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "DE",
    'description' => "Seit jeher umgibt die Alchemie die Aura des Geheimnisvoll-Verbotenen. Hervorgegangen in der Antike aus der wechselseitigen Durchdringung der ägyptischen und griechischen Kultur, war die Alchemie, wie ihre spannende und wechselvolle Geschichte zeigt, nie nur praktische Laborarbeit, etwa zu dem Behuf, den Stein der Weisen herzustellen. Vielmehr erschuf sie zugleich ein Weltbild, in dem Mensch und Natur, Geist und Materie aufs Engste miteinander verwoben sind. Nicht zuletzt dies ist der Grund für die bis heute anhaltende Faszination am alchemistischen Denken. ",
    'original_language' => "DE",
    'number_of_pages' => 128,
    'version' => 1,
    'type' => "Taschenbuch",
    'genre' => "Kochen"
  ));

BookModel::create(array(
    'name' => "Unter dem Jolly Roger: Piraten im Goldenen Zeitalter",
    'isbn' => "3862414000",
    'cover' => "theme/images/books/3862414000.jpg",
    'title' => "Unter dem Jolly Roger: Piraten im Goldenen Zeitalter",
    'author' => "Gabriel Kuhn",
    'year_of_publication' => 2011,
    'price' => 14.00,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "DE",
    'description' => "Die Piraten des Goldenen Zeitalters, die von 1690 bis 1725 die Meere zwischen der Karibik und dem Indischen Ozean unsicher machten, haben bis heute nur wenig an Faszination verloren. Die politische Deutung ihrer Aktivitäten ist jedoch umstritten. Das Buch untersucht die Kultur und Ökonomie, die moralischen Prinzipien und sozialen Organisationsformen der Piraten, um neue Perspektiven auf ihre Lebensweise zu eröffnen. Die Studie versteht sich als wissenschaftlicher Beitrag zur Piratenforschung, ein abschließender Essay untersucht die Bedeutung des Goldenen Zeitalters für politischen Aktivismus heute.",
    'original_language' => "DE",
    'number_of_pages' => 232,
    'version' => 1,
    'type' => "Taschenbuch",
    'genre' => "Fachliteratur"
  ));

BookModel::create(array(
    'name' => "How to Read a Book",
    'isbn' => "671212095",
    'cover' => "theme/images/books/671212095.jpg",
    'title' => "How to Read a Book",
    'author' => "Mortimer J. Adler, Charles Van Doren",
    'year_of_publication' => 1972,
    'price' => 17.00,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "EN",
    'description' => "Die Piraten des Goldenen Zeitalters, die von 1690 bis 1725 die Meere zwischen der Karibik und dem Indischen Ozean unsicher machten, haben bis heute nur wenig an Faszination verloren. Die politische Deutung ihrer Aktivitäten ist jedoch umstritten. Das Buch untersucht die Kultur und Ökonomie, die moralischen Prinzipien und sozialen Organisationsformen der Piraten, um neue Perspektiven auf ihre Lebensweise zu eröffnen. Die Studie versteht sich als wissenschaftlicher Beitrag zur Piratenforschung, ein abschließender Essay untersucht die Bedeutung des Goldenen Zeitalters für politischen Aktivismus heute.",
    'original_language' => "EN",
    'number_of_pages' => 426,
    'version' => 1,
    'type' => "Taschenbuch",
    'genre' => "Lernmittel"
  ));

BookModel::create(array(
    'name' => "JavaScript: The Definitive Guide:
Activate Your Web Pages (Definitive Guides)",
    'isbn' => "0596805527",
    'cover' => "theme/images/books/0596805527.jpg",
    'title' => "JavaScript: The Definitive Guide:
Activate Your Web Pages (Definitive Guides)",
    'author' => "David Flanagan",
    'year_of_publication' => 2014,
    'price' => 32.00,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "DE",
    'description' => "JavaScript: The Definitive Guide has been the bible for JavaScript programmers-a programmer's guide and comprehensive reference to the core language and to the client-side JavaScript APIs defined by web browsers.

The 6th edition covers HTML5 and ECMAScript 5. Many chapters have been completely rewritten to bring them in line with today's best web development practices. New chapters in this edition document jQuery and server side JavaScript. It's recommended for experienced programmers who want to learn the programming language of the Web, and for current JavaScript programmers who want to master it.",
    'original_language' => "EN",
    'number_of_pages' => 1076,
    'version' => 1,
    'type' => "Hardcover",
    'genre' => "Fachliteratur"
  ));

BookModel::create(array(
    'name' => "The Adventures of Sherlock Holmes",
    'isbn' => "149964244X",
    'cover' => "theme/images/books/149964244X.jpg",
    'title' => "The Adventures of Sherlock Holmes",
    'author' => " Sir Arthur Conan Doyle",
    'year_of_publication' => 2014,
    'price' => 8.00,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "EN",
    'description' => "These are the first of the Sherlock Holmes short stories, originally published as single stories in the Strand Magazine from July 1891 to June 1892. The book was published in England on 14 October 1892 by George Newnes Ltd and in a US Edition on 15 October by Harper. The initial combined print run was 14,500 copies.",
    'original_language' => "EN",
    'number_of_pages' => 202,
    'version' => 1,
    'type' => "Paperback",
    'genre' => "Krimi"
  ));

BookModel::create(array(
    'name' => "Globi in der Schule",
    'isbn' => "3857033312",
    'cover' => "theme/images/books/3857033312.jpg",
    'title' => "Globi in der Schule",
    'author' => "Samuel Glättli,  Jürg Lendenmann",
    'year_of_publication' => 2010,
    'price' => 12.00,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "DE",
    'description' => "Globi bewirbt sich auf ein Inserat und bekommt die Stelle! Jetzt ist er ein Jahr lang die rechte Hand des Schulabwarts Toni Gerber. Zusammen mit Toni, den Kindern, den Lehrerinnen und Lehrern und den Hortnerinnen und Hortnern erlebt Globi viele Abenteuer rund um Schule und Hort. Er erfährt wie es sein kann, eine Schulstunde abzuhalten oder die Pausenaufsicht zu machen. Er rettet Frank aus der Toilette, und schlichtet Streit. Er erfindet ein Abfallent-sorgungsspiel, baut einen Schulbus und Seifenkisten und mehr. Auch begleitet er seine Kinder auf die Schulreise und beim Verkehrsunterricht. Im Hort hilft Globi mit beim Kochen, beim Abwaschen, bei den Schulaufgaben und er feiert mit den Kindern auch einige Feste.",
    'original_language' => "DE",
    'number_of_pages' => 100,
    'version' => 1,
    'type' => "Bilderbuch",
    'genre' => "Kinderbuch"
  ));

BookModel::create(array(
    'name' => "The Name of the Wind (Kingkiller Chronicle)",
    'isbn' => "756404746",
    'cover' => "theme/images/books/756404746.jpg",
    'title' => "The Name of the Wind (Kingkiller Chronicle)",
    'author' => "Patrick Rothfuss",
    'year_of_publication' => 2008,
    'price' => 13.00,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "EN",
    'description' => "The plot is divided into two different action threads: the present, where Kvothe tells the story of his life to Devan Lochees (known as Chronicler) in the main room of his inn, and Kvothe's past, the story in question, which comprises the majority of the books. The present-day interludes are in the third person from the perspective of multiple characters, while the story of Kvothe's life is told entirely in the first person from his own perspective.",
    'original_language' => "EN",
    'number_of_pages' => 722,
    'version' => 1,
    'type' => "Paperback",
    'genre' => "Fantasy"
  ));

BookModel::create(array(
    'name' => "Die Schriften von Accra",
    'isbn' => "3257242824",
    'cover' => "theme/images/books/3257242824.jpg",
    'title' => "Die Schriften von Accra",
    'author' => "Paulo Coelho",
    'year_of_publication' => 2014,
    'price' => 15.00,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "DE",
    'description' => "Was bleibt, wenn alles verloren scheint?
14. Juli 1099. Vor den Toren Jerusalems steht das feindliche Heer der Kreuzritter. Im Morgengrauen werden sie angreifen. Es ist wohl die letzte Nacht, die Muslime, Juden und Christen friedlich in der Heiligen Stadt verbringen werden. Die meisten schärfen ihre Waffen. Doch da sind noch ein paar Männer und Frauen jeden Alters, die sich um einen geheimnisvollen Fremden scharen – den Kopten. Sie glauben, er werde sie auf den Kampf vorbereiten, aber da täuschen sie sich.
Einst fortgegangen aus seiner Heimat Griechenland, um die Welt zu erobern, hat der geheimnisvolle Fremde in Jerusalem einen Schatz gefunden, den ihm keiner mehr nehmen kann: Erkenntnisse über die wesentlichen Dinge im menschlichen Leben, über unsere Werte und Ziele, unsere Träume, die wir nicht aufschieben dürfen, über die Schwierigkeiten, die wir überwinden müssen und die uns nur stärker machen; Antworten auf existentielle Fragen, die uns auch heute noch beschäftigen.",
    'original_language' => "DE",
    'number_of_pages' => 214,
    'version' => 1,
    'type' => "Heft",
    'genre' => "Roman"
  ));

BookModel::create(array(
    'name' => "A Lucky Luke Adventure - Dalton City",
    'isbn' => "1905460139",
    'cover' => "theme/images/books/1905460139.jpg",
    'title' => "A Lucky Luke Adventure - Dalton City",
    'author' => "Goscinny",
    'year_of_publication' => 2007,
    'price' => 10.00,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "EN",
    'description' => "In this new volume, Lucky Luke has to clean out a whole city: Fenton Town, so named because it has been taken over by Dean Fenton, a desperado of the worst sort... Fenton Town has become the most depraved city in all of Texas. Lucky Luke makes a short visit to town—just the time needed to put Fenton in prison and chase out the remaining rabble.",
    'original_language' => "EN",
    'number_of_pages' => 42,
    'version' => 1,
    'type' => "Taschenbuch",
    'genre' => "Comic"
  ));

BookModel::create(array(
    'name' => "On my way to Samarkand: memoirs of a travelling writer",
    'isbn' => "1480208299",
    'cover' => "theme/images/books/1480208299.jpg",
    'title' => "On my way to Samarkand: memoirs of a travelling writer",
    'author' => "Garry Douglas Kilworth",
    'year_of_publication' => 2012,
    'price' => 12.00,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "EN",
    'description' => "Garry (Douglas) Kilworth is a varied and prolific writer who has travelled widely since childhood, living in a number of countries, especially in the Far East. His books include science fiction and fantasy, historical novels, literary novels, short story collections, children's books and film novelisations. This autobiography contains anecdotes about his farm worker antecedents and his rovings around the globe, as well as his experiences in the middle list of many publishing houses. The style is chatty, the structure loose - pole vaulting time and space on occasion - and the whole saga is an entertaining ramble through a 1950s childhood, foreign climes and the genre corridors of the literary world.",
    'original_language' => "EN",
    'number_of_pages' => 324,
    'version' => 1,
    'type' => "Audio-Buch",
    'genre' => "Reisen"
  ));

BookModel::create(array(
    'name' => "National Geographic Student World Atlas Fourth Edition",
    'isbn' => "1426317751",
    'cover' => "theme/images/books/1426317751.jpg",
    'title' => "National Geographic Student World Atlas Fourth Edition",
    'author' => "National Geographic",
    'year_of_publication' => 2014,
    'price' => 9.00,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "EN",
    'description' => "The new fourth edition of National Geographic's award-winning Student Atlas of the World is more fascinating and fact-filled than ever, and has gone INTERACTIVE with new digital extras, including scannable pages that link to photo galleries and quizzes. Dynamic, user-friendly content includes photos, facts, charts, graphics, and full-color political, physical, and thematic maps on important topics. From the cartographic experts at National Geographic comes the latest edition of its award-winning student atlas, with everything kids want and need to know about our changing world!",
    'original_language' => "EN",
    'number_of_pages' => 144,
    'version' => 4,
    'type' => "Taschenbuch",
    'genre' => "Geografie"
  ));

BookModel::create(array(
    'name' => "Omas Kochkniffkladde: Die 500 pfiffigsten Kochtipps",
    'isbn' => "3939722847",
    'cover' => "theme/images/books/3939722847.jpg",
    'title' => "Omas Kochkniffkladde: Die 500 pfiffigsten Kochtipps",
    'author' => "Regionalia Verlag",
    'year_of_publication' => 2014,
    'price' => 13.00,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "DE",
    'description' => "Keine Beschreibung vorhanden.",
    'original_language' => "DE",
    'number_of_pages' => 128,
    'version' => 2,
    'type' => "E-Book",
    'genre' => "Kochen"
  ));

BookModel::create(array(
    'name' => "Show Me How: 500 Things You Should Know -
Instructions for Life from the Everyday to the Exotic",
    'isbn' => "0061662577",
    'cover' => "theme/images/books/0061662577.jpg",
    'title' => "Show Me How: 500 Things You Should Know -
Instructions for Life from the Everyday to the Exotic",
    'author' => "Lauren Smith, Derek Fagerstrom",
    'year_of_publication' => 2008,
    'price' => 12.00,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "EN",
    'description' => "Keine Beschreibung vorhanden.",
    'original_language' => "EN",
    'number_of_pages' => 320,
    'version' => 4,
    'type' => "Heft",
    'genre' => "Fachliteratur"
  ));

BookModel::create(array(
    'name' => "Vegetarisch! Das Goldene von GU: Rezepte zum Glänzen und Genießen",
    'isbn' => "3833822015",
    'cover' => "theme/images/books/3833822015.jpg",
    'title' => "Vegetarisch! Das Goldene von GU: Rezepte zum Glänzen und Genießen",
    'author' => "Alessandra Redies",
    'year_of_publication' => 2011,
    'price' => 22.00,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "DE",
    'description' => "Keine Beschreibung vorhanden.",
    'original_language' => "DE",
    'number_of_pages' => 600,
    'version' => 1,
    'type' => "Hardcover",
    'genre' => "Kochen"
  ));

BookModel::create(array(
    'name' => "Im Tal des Fuchses",
    'isbn' => "3442382599",
    'cover' => "theme/images/books/3442382599.jpg",
    'title' => "Im Tal des Fuchses",
    'author' => "Charlotte Link",
    'year_of_publication' => 2013,
    'price' => 9.95,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "DE",
    'description' => "Was, wenn dein Entführer spurlos verschwindet und niemand weiß, wo du bist?


Ein sonniger Augusttag, ein einsam gelegener Parkplatz zwischen Wiesen und Feldern. Vanessa Willard wartet auf ihren Mann, der noch eine Runde mit dem Hund dreht. In Gedanken versunken bemerkt sie nicht das Auto, das sich nähert. Als sie ein unheimliches Gefühl beschleicht, ist es schon zu spät: Ein Fremder taucht auf, überwältigt, betäubt und verschleppt sie. In eine Kiste gesperrt, wird sie in einer Höhle versteckt, ausgestattet mit Wasser und Nahrung für eine Woche. Doch noch ehe der Täter seine Lösegeldforderung an ihren Mann stellen kann, wird er wegen eines anderen Deliktes verhaftet. Und überlässt Vanessa ihrem Schicksal …",
    'original_language' => "DE",
    'number_of_pages' => 567,
    'version' => 3,
    'type' => "Heft",
    'genre' => "Krimi"
  ));

BookModel::create(array(
    'name' => "Die Eule mit der Beule",
    'isbn' => "3789167061",
    'cover' => "theme/images/books/3789167061.jpg",
    'title' => "Die Eule mit der Beule",
    'author' => "Susanne Weber",
    'year_of_publication' => 2013,
    'price' => 5.95,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "DE",
    'description' => "Arme kleine Eule! Ein wunderbares Trostbuch.
Die kleine Eule hat eine Beule. Der Fuchs pustet, die Maus bringt ein Pflaster und die Schlange streichelt ihr die Wange. Doch was hilft am besten? Natürlich ein Kuss von der Mama! Ein absolutes Lieblingsbuch für kleine Kinder mit der süßesten Eule der Welt. Einfach zum Liebhaben und Mitfühlen! Klare Bilder und einfache Reime zum Mitsprechen sorgen für viel Spaß beim Lesen.",
    'original_language' => "DE",
    'number_of_pages' => 132,
    'version' => 1,
    'type' => "Taschenbuch",
    'genre' => "Kinderbuch"
  ));

BookModel::create(array(
    'name' => "Der Drachenflüsterer",
    'isbn' => "3570400026",
    'cover' => "theme/images/books/3570400026.jpg",
    'title' => "Der Drachenflüsterer",
    'author' => "Boris Koch",
    'year_of_publication' => 2010,
    'price' => 7.95,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "DE",
    'description' => "Märchenhaft und abenteuerlich


Der „Drachenflüsterer“ erzählt packend und humorvoll von Freundschaft, erster Liebe und unfreiwilligem Heldentum. Eine großartige Mischung aus Buddy Movie, Huckleberry Finn und klassischer Fantasy!


Der fünfzehnjährige Ben ist ein Waisenkind und lebt im langweiligsten Dorf der Welt. Noch weiß er es nicht, doch er verfügt über eine besondere Gabe: Er ist ein Drachenflüsterer. Als er mit den falschen Leuten Ärger bekommt, lässt er alles hinter sich und erringt die Freundschaft eines Großen Drachens. Nur warum muss es ausgerechnet ein Drache mit psychischen Problemen sein …?


All-Age-Fantasy für alle Fans von Cornelia Funke, Kai Meyer, Philip Pullman und Jonathan Stroud",
    'original_language' => "DE",
    'number_of_pages' => 354,
    'version' => 3,
    'type' => "Taschenbuch",
    'genre' => "Fantasy"
  ));


BookModel::create(array(
    'name' => "Honigtod",
    'isbn' => "1495319792",
    'cover' => "theme/images/books/1495319792.jpg",
    'title' => "Honigtod",
    'author' => "Hanni Münzer",
    'year_of_publication' => 2014,
    'price' => 3.99,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "DE",
    'description' => "Als sich die junge Amerikanerin Felicity 2012 auf die Suche nach ihrer Mutter macht, stößt sie in Rom auf die Spuren ihrer eigenen Familienvergangenheit - und auf ein quälendes Geheimnis aus der dunkelsten Zeit unserer Geschichte.
Ungewollt tritt Felicity eine Reise in die Vergangenheit an und erfährt die Geschichte ihrer Urgroßmutter Elisabeth und deren Tochter Deborah.
Ein Netz aus schicksalhafter Liebe, Schuld und Sühne zerstörte das Leben der beiden Frauen und warf über Generationen einen Schatten auf Felicitys eigenes Leben.

„HONIGTOT“ ist eine Geschichte über Liebe und Obsession, Schuld und Sühne, Verrat und Rache ... bis zum bittersüßen Ende. ",
    'original_language' => "DE",
    'number_of_pages' => 406,
    'version' => 1,
    'type' => "E-Book",
    'genre' => "Roman"
  ));

BookModel::create(array(
    'name' => "Guardians of the Galaxy: Bd. 1 Originalstories:
Guardians of the Galaxy 0.1-3, Guardians of the Galaxy:
Tomorrow's Avengers 1",
    'isbn' => "3862018822",
    'cover' => "theme/images/books/3862018822.jpg",
    'title' => "Guardians of the Galaxy: Bd. 1 Originalstories:
Guardians of the Galaxy 0.1-3, Guardians of the Galaxy:
Tomorrow's Avengers 1",
    'author' => "Steve McNiven ,Sara Pichelli ",
    'year_of_publication' => 2014,
    'price' => 9.99,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "EN",
    'description' => "Guardians of the Galaxy, Better than ever. There has never been a Guardians of the Galaxy Guide like this. <p><p>It contains 74 answers, much more than you can imagine; comprehensive answers and extensive details and references, with insights that have never before been offered in print. Get the information you need--fast! This all-embracing guide offers a thorough view of key knowledge and detailed insight. This Guide introduces what you want to know about Guardians of the Galaxy. <p><p>A quick look inside of some of the subjects covered: Guardians of the Galaxy (2008 team) - War of Kings, Cloak of Levitation - Guardians of the Galaxy, Guardians of the Galaxy (soundtrack)",
    'original_language' => "EN",
    'number_of_pages' => 115,
    'version' => 1,
    'type' => "E-Book",
    'genre' => "Comic"
  ));

BookModel::create(array(
    'name' => "400 Reisen, die Sie nie vergessen werden",
    'isbn' => "3866902409",
    'cover' => "theme/images/books/3866902409.jpg",
    'title' => "400 Reisen, die Sie nie vergessen werden",
    'author' => "Keith Bellows",
    'year_of_publication' => 2011,
    'price' => 21.99,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "EN",
    'description' => "Goethe beschrieb es als beste Bildung überhaupt, Tucholsky als Sehnsucht nach leben. Reisen und die Lust darauf treiben uns seit Jahrtausenden an. Goethe beschrieb es als beste Bildung überhaupt, Tucholsky als Sehnsucht nach leben. Reisen und die Lust darauf treiben uns seit Jahrtausenden an. NATIONAL GEOGRAPHIC hat die schönsten Fernweh-Ziele in einem Bildband gesammelt - in aktualisierter Auflage, im Softcover zum Nachschlagen und Planen, wohin die nächste Reise geht: von den Bergen Kasachstans bis zum Baikalsee, von den Hochanden bis in den Regenwald; durchs Australische Outback oder die Amalfiküste entlang. Je nach Geschmack, Typ und Geldbeutel nimmt der Bildband den Leser mit, entweder zu Fuß, per Schiff, im luxuszug oder per Pferdekarren ... ein unterhaltsames Kompendium für Abenteurer, Genießer, Kulturfreunde und Globetrotter. 400 Traumreisen, getestet und für unbedingt nachahmenswert befunden. Übersichtskarten, verführerische Fotos, Top-10-Listen und viele nützliche Infos sorgen dafür, dass unterwegs durch die Welt auch nichts schiefgeht. Was fehlt? Kofferpacken müssen Sie allein.",
    'original_language' => "EN",
    'number_of_pages' => 84,
    'version' => 1,
    'type' => "Audio-Buch",
    'genre' => "Reisen"
  ));

BookModel::create(array(
    'name' => "Geografie - Brandenburg Grundschule: 5./6. Schuljahr - Arbeitsheft",
    'isbn' => "3060646163",
    'cover' => "theme/images/books/3060646163.jpg",
    'title' => "Geografie - Brandenburg Grundschule: 5./6. Schuljahr - Arbeitsheft",
    'author' => "Silke Schulze",
    'year_of_publication' => 2004,
    'price' => 7.85,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "DE",
    'description' => "oethe beschrieb es als beste Bildung überhaupt, Tucholsky als Sehnsucht nach leben. Reisen und die Lust darauf treiben uns seit Jahrtausenden an. NATIONAL GEOGRAPHIC hat die schönsten Fernweh-Ziele in einem Bildband gesammelt - in aktualisierter Auflage, im Softcover zum Nachschlagen und Planen, wohin die nächste Reise geht: von den Bergen Kasachstans bis zum Baikalsee, von den Hochanden bis in den Regenwald; durchs Australische Outback oder die Amalfiküste entlang. Je nach Geschmack, Typ und Geldbeutel nimmt der Bildband den Leser mit, entweder zu Fuß, per Schiff, im luxuszug oder per Pferdekarren ... ein unterhaltsames Kompendium für Abenteurer, Genießer, Kulturfreunde und Globetrotter. 400 Traumreisen, getestet und für unbedingt nachahmenswert befunden. Übersichtskarten, verführerische Fotos, Top-10-Listen und viele nützliche Infos sorgen dafür, dass unterwegs durch die Welt auch nichts schiefgeht. Was fehlt? Kofferpacken müssen Sie allein.",
    'description' => "",
    'original_language' => "DE",
    'number_of_pages' => 32,
    'version' => 1,
    'type' => "Taschenbuch",
    'genre' => "Geografie
"
  ));

BookModel::create(array(
    'name' => "Wochenplan Deutsch 4. Schuljahr:
Jede Woche übersichtlich auf einem Bogen",
    'isbn' => "3866320647",
    'cover' => "theme/images/books/3866320647.jpg",
    'title' => "Wochenplan Deutsch 4. Schuljahr:
Jede Woche übersichtlich auf einem Bogen",
    'author' => "Ulrike Stolz",
    'year_of_publication' => 2013,
    'price' => 17.80,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "DE",
    'description' => "Die Wochenplanarbeit ermöglicht den Schülern, ihr individuelles Lerntempo zu wählen. Damit können Sie konsequent am jeweiligen Thema arbeiten. Gleichzeitig werden die verschiedenen heterogenen Niveaustufen einer Klasse berücksichtigt. Jede Woche ist in fünf Einheiten auf einem Bogen zusammengefasst. Wann Sie welchen Bogen einsetzen, wählen Sie selbst ? ganz individuell nach dem jeweils zu behandelnden Thema. Sie tragen nur noch die Wochennummer bzw. das Datum ein, ",
    'original_language' => "DE",
    'number_of_pages' => 80,
    'version' => 1,
    'type' => "Taschenbuch",
    'genre' => "Lehrmittel"
  ));

BookModel::create(array(
    'name' => "Fachkunde für Garten- und Landschaftsbau: Lehrbuch",
    'isbn' => "3582041565",
    'cover' => "theme/images/books/3582041565.jpg",
    'title' => "Fachkunde für Garten- und Landschaftsbau: Lehrbuch",
    'author' => "Holger Seipel",
    'year_of_publication' => 2013,
    'price' => 45.80,
    'currency' => "CHF",
    'available' => "Sofort",
    'language' => "DE",
    'description' => "Alles für den Gartenfreund In unserem Garten-Shop finden Sie alles, was Sie zum Gärtnern, Gestalten und Genießen brauchen: Gartenmöbel, Gartenwerkzeug, Gartendekoration, Blumen & Pflanzen und vieles mehr.
Neu bei Amazon.de: Der Gartenkalender für einen schnellen Überblick jeden Monat.",
    'original_language' => "DE",
    'number_of_pages' => 548,
    'version' => 1,
    'type' => "Taschenbuch",
    'genre' => "Fachliteratur"
  ));

}

//Check if there is the type Table, if not, create some entries
if(!$db->tableExists('type')) {
    TypeModel::create(array(
      'key' => 'pocketbook'
    ));
    TypeModel::create(array(
      'key' => 'audio_book'
    ));
    TypeModel::create(array(
      'key' => 'ebook'
    ));
    TypeModel::create(array(
      'key' => 'hard_cover'
    ));
    TypeModel::create(array(
      'key' => 'leaflet'
    ));
    TypeModel::create(array(
      'key' => 'comic'
    ));
    TypeModel::create(array(
      'key' => 'booklet'
    ));
  }


if(!$db->tableExists('genre')) {

    GenreModel::create(array(
      'key' => 'science'
    ));
    GenreModel::create(array(
      'key' => 'fantasy'
    ));
    GenreModel::create(array(
      'key' => 'crime'
    ));
    GenreModel::create(array(
      'key' => 'childrens_book'
    ));
    GenreModel::create(array(
      'key' => 'horror'
    ));
    GenreModel::create(array(
      'key' => 'art'
    ));
    GenreModel::create(array(
      'key' => 'sports'
    ));
    GenreModel::create(array(
      'key' => 'roman'
    ));
    GenreModel::create(array(
      'key' => 'comic'
    ));
    GenreModel::create(array(
      'key' => 'travelling'
    ));
    GenreModel::create(array(
      'key' => 'geographic'
    ));
    GenreModel::create(array(
      'key' => 'professional_literature'
    ));
    GenreModel::create(array(
      'key' => 'cooking'
    ));
  }

  if(!$db->tableExists('bookgenre')) {
    BookGenreModel::create(array(
        'book_id' => 0,
        'genre_id' => 2
    ));
    BookGenreModel::create(array(
        'book_id' => 1,
        'genre_id' => 3
    ));
    BookGenreModel::create(array(
        'book_id' => 2,
        'genre_id' => 4
    ));
    BookGenreModel::create(array(
        'book_id' => 3,
        'genre_id' => 2
    ));
    BookGenreModel::create(array(
        'book_id' => 4,
        'genre_id' => 8
    ));
    BookGenreModel::create(array(
        'book_id' => 5,
        'genre_id' => 9
    ));
    BookGenreModel::create(array(
        'book_id' => 6,
        'genre_id' => 10
    ));
    BookGenreModel::create(array(
        'book_id' => 7,
        'genre_id' => 10
    ));
    BookGenreModel::create(array(
        'book_id' => 8,
        'genre_id' => 13
    ));
    BookGenreModel::create(array(
        'book_id' => 9,
        'genre_id' => 12
    ));
    BookGenreModel::create(array(
        'book_id' => 10,
        'genre_id' => 12
    ));

    BookGenreModel::create(array(
        'book_id' => 11,
        'genre_id' => 12
    ));
    BookGenreModel::create(array(
        'book_id' => 12,
        'genre_id' => 3
    ));
    BookGenreModel::create(array(
        'book_id' => 13,
        'genre_id' => 4
    ));
    BookGenreModel::create(array(
        'book_id' => 14,
        'genre_id' => 2
    ));
    BookGenreModel::create(array(
        'book_id' => 15,
        'genre_id' => 8
    ));
    BookGenreModel::create(array(
        'book_id' => 16,
        'genre_id' => 9
    ));
    BookGenreModel::create(array(
        'book_id' => 17,
        'genre_id' => 10
    ));
    BookGenreModel::create(array(
        'book_id' => 18,
        'genre_id' => 11
    ));
    BookGenreModel::create(array(
        'book_id' => 19,
        'genre_id' => 13
    ));
    BookGenreModel::create(array(
        'book_id' => 20,
        'genre_id' => 12
    ));

    BookGenreModel::create(array(
        'book_id' => 21,
        'genre_id' => 13
    ));
    BookGenreModel::create(array(
        'book_id' => 22,
        'genre_id' => 3
    ));
    BookGenreModel::create(array(
        'book_id' => 23,
        'genre_id' => 4
    ));
    BookGenreModel::create(array(
        'book_id' => 24,
        'genre_id' => 2
    ));
    BookGenreModel::create(array(
        'book_id' => 25,
        'genre_id' => 8
    ));
    BookGenreModel::create(array(
        'book_id' => 26,
        'genre_id' => 9
    ));
    BookGenreModel::create(array(
        'book_id' => 27,
        'genre_id' => 10
    ));
    BookGenreModel::create(array(
        'book_id' => 28,
        'genre_id' => 11
    ));
    BookGenreModel::create(array(
        'book_id' => 29,
        'genre_id' => 12
    ));
    BookGenreModel::create(array(
        'book_id' => 30,
        'genre_id' => 12
    ));
}

if(!$db->tableExists('order')) {
  OrderModel::create(array(
    'user_id' => 1,
    'datetime' => "2014-11-11 11:11:11"
  ));
}

if(!$db->tableExists('position')) {
  PositionModel::create(array(
    'order_id' => 1,
    'book_id' => 1,
    'amount' => 1,
    'price' => 12.50
  ));
}

?>