<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Доставка тест");
?><script src="https://api-maps.yandex.ru/2.0-stable/?load=package.full&lang=ru-RU&coordorder=longlat"
            type="text/javascript"></script> <script type="text/javascript">
        ymaps.ready(init);
        function init() {
            var map;
            ymaps.ready(function () {
                map = new ymaps.Map("map", {
                    center: [82.9576116083665, 55.0224920350782],
                    zoom: 11,
                    size: [null, 400]
                });
            });

            var geoObjects = {
                "type": "FeatureCollection",
                "features": [{
                    "type": "Feature",
                    "properties": {"hintContent": "Левый берег", "description": ""},
                    "geometry": {
                        "type": "Polygon",
                        "coordinates": [[[82.83442765014284, 55.01545488619978], [82.82791882622139, 55.01715459565025], [82.8191426383239, 55.01870809573258], [82.81319886314652, 55.02134644144969], [82.81221181022906, 55.020569750292005], [82.81148224937772, 55.02039715020911], [82.81107346498217, 55.02011824549682], [82.81064431153985, 55.0195511217233], [82.80716816865656, 55.01735652334623], [82.80539791070719, 55.01636398276086], [82.80482928239648, 55.01517413451563], [82.81928412759017, 55.0086555054844], [82.82024590992816, 55.00692508400498], [82.82034938538142, 55.00519458754649], [82.82196491683332, 54.997877115808066], [82.79601354565501, 54.99656321426566], [82.79881283940729, 54.98157504229787], [82.84443920207667, 54.96077461227313], [82.85435999833977, 54.957550790589075], [82.8503688713261, 54.944138604388144], [82.86015356981206, 54.93849327411628], [82.86424125635101, 54.93489195281674], [82.86353315317076, 54.93394678395121], [82.86369408571171, 54.9332795924984], [82.86299671136783, 54.93190193052513], [82.86484207116992, 54.931389156336046], [82.86427344285902, 54.93046861753112], [82.86690200769368, 54.92886225752159], [82.8684469600862, 54.928794294700396], [82.87313546144402, 54.932124336532155], [82.87753845252466, 54.93196574118696], [82.87784472841838, 54.93383442610791], [82.90853544921158, 54.931390633342126], [82.91768007191669, 54.93065822189911], [82.92233638676676, 54.928072580719814], [82.93583125772092, 54.934925761368476], [82.93884333481579, 54.93771661537982], [82.97008570542137, 54.950389947305176], [82.97118004669936, 54.95218686394282], [82.97347601761624, 54.95388490689009], [82.97446307053421, 54.95461349959269], [82.97630485960502, 54.954646064760325], [82.9784291691448, 54.95690585076873], [82.98150834509414, 54.957788737945044], [82.98192676970055, 54.95847404254], [82.98171219297934, 54.95785665155081], [82.98076805540605, 54.95775786810116], [82.98149170586, 54.95765655974255], [82.98185648628593, 54.9579529100938], [82.9813629598271, 54.95790351852122], [82.9819637746461, 54.957952910093624], [82.98166336723642, 54.95800230160526], [82.98151316353163, 54.95792821430429], [82.98203887649858, 54.95885429557357], [82.98126103588429, 54.958286301601156], [82.97909917541854, 54.959014814146705], [82.97643305965768, 54.960533534915974], [82.97041954704628, 54.96417576033549], [82.96445431419711, 54.967163372198335], [82.96166481682114, 54.970397642528205], [82.95788826652837, 54.97301449422886], [82.94910393206789, 54.973340279294355], [82.94720085480348, 54.97556441295225], [82.94576984632582, 54.97699854229342], [82.94564110029344, 54.97712196215913], [82.94173580396804, 54.978356139920784], [82.9377446769535, 54.98079969946676], [82.93011291278918, 54.98292111079238], [82.927373497868, 54.98617762948796], [82.91492804803892, 54.992630495463985], [82.91758879938169, 54.99461673201366], [82.92703017511415, 55.001499942941024], [82.91454180994056, 55.0093685408636], [82.90226802148854, 55.01642182781688], [82.89025172510178, 55.02068772171624], [82.86935195245769, 55.00685273143199], [82.86796793260602, 55.00811373903052], [82.85731419839878, 55.01282751993241], [82.84489020624201, 55.01329609298067], [82.83442765014284, 55.01545488619978]]]
                    },
                    "options": {
                        "color": "#ed4543", // красная
                        "zIndex": 100000000
                    }
                }, {
                    "type": "Feature",
                    "properties": {"hintContent": "Правый берег", "description": ""},
                    "geometry": {
                        "type": "Polygon",
                        "coordinates": [[[82.95758758330933, 54.995242461802796], [82.95170626718472, 54.99758310647273], [82.94692120630248, 55.00030302465396], [82.94133711531414, 55.00288455642565], [82.94138794319456, 55.00400511606897], [82.94133148271449, 55.004848143591886], [82.9410578973951, 55.005985884111624], [82.9398374922934, 55.007596856941554], [82.93732962686451, 55.009010451335854], [82.93626747209477, 55.00918310065406], [82.92758784372221, 55.01238930954428], [82.91754488941285, 55.01765276603706], [82.92049570120781, 55.019589226191336], [82.91785123405835, 55.02028619191634], [82.91265330392685, 55.02142696623902], [82.9091342456991, 55.02229610092698], [82.9056581028164, 55.024397972556066], [82.90282569009695, 55.02699896247922], [82.89903330692078, 55.03016685242985], [82.89723086246346, 55.03220052230374], [82.89478468784108, 55.03582389534438], [82.89154457935135, 55.039508552825176], [82.88993525394272, 55.04090099361427], [82.88770365604195, 55.04478231969553], [82.88352765896921, 55.04262115604764], [82.8824159845148, 55.04356129895196], [82.88132576773268, 55.0444398138579], [82.87894396612738, 55.04533924958884], [82.8705540163283, 55.04760623045645], [82.87139086554082, 55.048283835583725], [82.87360100576882, 55.04933102086083], [82.87576823065244, 55.050205707485006], [82.88072495291372, 55.052324587079355], [82.88357125118426, 55.053431472735774], [82.89036260440965, 55.056184539589516], [82.88995624974378, 55.05637469125549], [82.88980738714349, 55.056786555154126], [82.89002464607368, 55.057314658517306], [82.89024190500368, 55.058507869208], [82.89056645229445, 55.060144434295445], [82.89092288730852, 55.061707682521096], [82.89119766433987, 55.06302500858498], [82.89131359518358, 55.06373291617552], [82.8906570498309, 55.056287450010004], [82.89979801815475, 55.06838141493982], [82.90900335949294, 55.0711642315194], [82.91151390713154, 55.070031426973514], [82.91711435955506, 55.07040082329305], [82.91975365322446, 55.07160749407137], [82.91850910824355, 55.07221081613794], [82.92271481197899, 55.07448858093746], [82.92552576702468, 55.07266637950365], [82.93363676708832, 55.076987816206994], [82.94254170101637, 55.07606447166095], [82.97821029853407, 55.09453191786001], [82.9901382054155, 55.089695278153776], [82.98660841835317, 55.07654287405569], [82.97854033363627, 55.07005431420412], [82.99919276722743, 55.0716018424171], [83.00296931752025, 55.06879442909403], [83.00969627362204, 55.061139365883136], [83.01089790326037, 55.06072523768356], [83.01141288739161, 55.06011404435249], [83.0136444852906, 55.05857139379997], [83.01870849591164, 55.05806024283696], [83.0251994417282, 55.0557569033314], [83.02672830086624, 55.05473760230553], [83.02714940768088, 55.052380176413735], [83.02821424466062, 55.049997972201986], [83.03138998013485, 55.04816233055791], [83.03330997630745, 55.047477703468786], [83.03937130319994, 55.04393461794363], [83.04178886848956, 55.042535322567815], [83.03407841253842, 55.040963466967995], [83.0312337111961, 55.04066012756351], [83.02565471644482, 55.039763666944275], [83.02612678523104, 55.036562737649874], [83.02738474125876, 55.03323214295281], [83.0277307462223, 55.031392648147694], [83.02835244653993, 55.02977646830001], [83.02930655632764, 55.02702316999146], [83.029815416035, 55.02623145227118], [83.02903429980378, 55.02540831117366], [83.02879082768676, 55.02483774091634], [83.02692401021251, 55.022914646315655], [83.02454928221293, 55.02142410956852], [83.02143791975543, 55.019303587879165], [83.01665285887258, 55.01718295353059], [83.0122199181431, 55.01667858865032], [83.00978254092098, 55.01576732835943], [83.00679606162366, 55.01604089183117], [83.00171939073056, 55.017234948425255], [83.00419582042342, 55.01990844733243], [83.00822889706353, 55.02308509227947], [83.01187573560473, 55.02253861939068], [83.01565035446134, 55.02271544381019], [83.01637903123589, 55.02330667716135], [83.0188033061458, 55.023855005837255], [83.02159258250201, 55.02541736521014], [83.02019761279479, 55.02794112436293], [83.01487611010899, 55.02694884696984], [83.01446304992116, 55.02840951999736], [83.01079647019786, 55.02893954002427], [83.0074611432878, 55.02874848710541], [83.0063788719506, 55.02967292822263], [83.00616706522835, 55.03075168599724], [83.0058694278182, 55.03146066073049], [83.00425733241025, 55.031297081453985], [83.0036322899193, 55.03167581048155], [82.99994157031423, 55.02966676534686], [82.9992273340447, 55.02693095337781], [82.99857747079118, 55.02502088316506], [82.98931694792908, 55.01576329314916], [82.9872140960617, 55.01467823526723], [82.98184967803181, 55.011102267639686], [82.97326660918361, 55.00594737831627], [82.97202206420077, 55.00511180425699], [82.97090626525058, 55.0042268783619], [82.96670056151534, 55.005913462342015], [82.96560841964921, 55.00402643105635], [82.96424137563133, 55.00267146516803], [82.95758758330933, 54.995242461802796]]]
                    },
                    "options": {
                        "color": "#f371d1", // фиолетовая
                        "strokeColor": "#b51eff",
                        "zIndex": 100000001
                    }
                }, {
                    "type": "Feature",
                    "properties": {"hintContent": "Правый берег", "description": ""},
                    "geometry": {
                        "type": "Polygon",
                        "coordinates": [
                            [[82.92829781629729, 55.100714426301614], [82.92838364698648, 55.10059138828932], [82.92842656233039, 55.10064060352521], [82.92816907026491, 55.10051756527294], [82.92827635862564, 55.100591388257875], [82.92813235164505, 55.10067751802768], [82.93044358350937, 55.11087605315715], [82.93372660734381, 55.1181296945714], [82.96546250440798, 55.11035327248705], [82.96919613935675, 55.11250431713676], [82.97166377165041, 55.11333379898846], [82.9736164198134, 55.11386807147678], [82.98601895429837, 55.109032451905655], [82.97885209181068, 55.09494353378489], [82.96273738004909, 55.08657486860743], [82.9537144289228, 55.08195904325645], [82.94492751219, 55.07740423780104], [82.94288903333876, 55.07637010129959], [82.93401628591755, 55.07720726149266], [82.92754679777333, 55.073809266786206], [82.92606621839708, 55.07293509807247], [82.92304068662882, 55.074708039943985], [82.91866332151633, 55.072479537741806], [82.91992932417132, 55.07176540569835], [82.91749387838577, 55.07049717413238], [82.91593819715715, 55.07038635587745], [82.9121187315197, 55.07011546547155], [82.90950089552206, 55.071396022040695], [82.90702253439173, 55.07049717412944], [82.89978057005115, 55.06846545687277], [82.89979129888735, 55.06857628048964], [82.8995874510024, 55.06828075016459], [82.89952307798613, 55.068206867246666], [82.89952576019517, 55.068129905719665], [82.89963573076469, 55.068249965634564], [82.89972692587084, 55.068453143123975], [82.8997591123792, 55.06858859421177], [82.8997483835433, 55.06837310364056], [82.89084497000889, 55.05685192830281], [82.89353638746445, 55.06856396677413], [82.8972271070689, 55.08117122227899], [82.92048722364638, 55.08294379756442], [82.93168812849257, 55.089713324219055], [82.92819052793728, 55.10072673008185], [82.92834073164201, 55.10059138828753], [82.92822807886326, 55.10065598328539], [82.92820125677281, 55.100548324884514], [82.92829781629729, 55.100714426301614]],
                            [[83.0042367303821, 54.999718403115004], [83.00219825153086, 55.00130958524383], [83.00697258357735, 55.00317205122656], [83.00831368808475, 55.0044424252238], [83.01596888676272, 55.007739547947835], [83.02272805348021, 55.00910843349328], [83.029723254591, 55.00500163605321], [83.02855647926253, 55.00804805838895], [83.03552216166722, 55.00591469182481], [83.036208807175, 55.00168422226045], [83.0266386854099, 55.00001904574785], [83.01344221705662, 54.996515781090025], [83.0063611852573, 54.995023099641415], [83.0050951826023, 54.99621971648184], [83.00522392863499, 54.996935202208256], [83.00496643656952, 54.99709556793533], [83.00462311381568, 54.99728060452414], [83.0042367303821, 54.999718403115004]]
                        ]
                    },
                    "options": {
                        "color": "#56db40", // зеленая
                        "strokeColor": "#1bad03",
                        "zIndex": 100000002
                    }
                }]
            };

            var q = geoObjects.features;
            for (var n = 0, t = q.length; n < t; n++) {
                var s = q[n];
                map.geoObjects.add(new ymaps.GeoObject({geometry: s.geometry, properties: s.properties}, {
                    strokeWidth: 1,
                    strokeColor: s.options.strokeColor || s.options.color,
                    fillColor: s.options.color,
                    fillOpacity: 0.6,
                    zIndex: (s.options && s.options.zIndex) || (n + 1)
                }))
            }

            // placemark
            var placemark = new ymaps.Placemark([82.974329, 55.040977], {
//                hintContent: 'Кухня доставки SUSHMAN&PIZZMAN: улица Кошурникова, 29/4 (цоколь)',
                balloonContent: 'Кухня доставки SUSHMAN&PIZZMAN: улица Кошурникова, 29/4 (цоколь)'
            }, {
                iconLayout: 'default#image',
                iconImageHref: '/bitrix/templates/sushman/img/ya-point.png',
                iconImageSize: [39, 42],
                iconImageOffset: [-20, -40]
            });
            map.geoObjects.add(placemark);

            // zoom control
            map.behaviors.enable('scrollZoom');
            map.controls.add(
                new ymaps.control.ZoomControl({noTips: true})
            );
        }
    </script>
<div id="map" style="height: 400px;">
</div>
<h3 class="" style="text-align: center;">СУММА МИНИМАЛЬНОГО ЗАКАЗА ДЛЯ БЕСПЛАТНОЙ ДОСТАВКИ </h3>
<table cellpadding="1" cellspacing="1" align="center" class="no-border">
<tbody>
<tr>
	<td style="width: 33%;">
 <img width="198" src="/upload/medialibrary/504/50474198925a9a02bb63c31ced113305.png" height="198" alt="Центр" title="Центр" align="middle">
	</td>
	<td style="width: 33%;">
 <img width="198" alt="Правый берег" src="/upload/medialibrary/a65/a653a27ee3b79ee6e4bf34c65a34e3a1.png" height="198" title="Правый берег" align="middle">&nbsp;
	</td>
	<td style="width: 33%;">
 <img width="198" alt="Левый берег" src="/upload/medialibrary/4ff/4ff247bfba3e15006dde250baee53a70.png" height="198" title="Левый берег" align="middle">&nbsp;
	</td>
</tr>
<tr>
	<td style="text-align: center;">
		 ЦЕНТР
	</td>
	<td style="text-align: center;">
		 ПРАВЫЙ БЕРЕГ
	</td>
	<td style="text-align: center;">
		 ЛЕВЫЙ БЕРЕГ
	</td>
</tr>
</tbody>
</table>
<hr style="margin-top: 30px; margin-bottom: 30px;">
<p>
	 При сумме заказа на меньшую стоимость — стоимость доставки 200 рублей.&nbsp;
</p>
<p>
	 В районы, не отмененные на карте, доставку не осуществляем.
</p>
<p>
	 Время доставки заказа варьируется от 40 минут до 4 часов. И зависит от загруженности производственных мощностей, ситуации на дорогах города, форс-мажорных обстоятельств (обстоятельств непреодолимой силы, которые невозможно было предвидеть на момент приёма заказа).
</p>
<p>
	 Уточнить все условия доставки можно у оператора по телефону&nbsp;<b>+7 (383) 319-55-55</b>.
</p>
<table cellpadding="1" cellspacing="1" class="no-border">
<tbody>
<tr>
	<td style="width: 100px;">
 <img width="93" src="/upload/medialibrary/a16/a164cff81a98b7fb28bb6011896a1843.png" height="93" title="Доставка" alt="Доставка">
	</td>
	<td style="vertical-align: middle;">
		 Кухня доставки <b>SUSHMAN&amp;PIZZMAN</b> находится по адресу:<br>
		 г. Новосибирск,&nbsp;улица Кошурникова, 29/4 (цоколь).
	</td>
</tr>
<tr>
	<td >
 <img width="93" alt="Время работы" src="/upload/medialibrary/1b7/1b797d7d630961da0be10840758b97f0.png" height="93" title="Время работы">
	</td>
	<td style="vertical-align: middle;">
 <b>Режим работы:</b><br>
		 Пн-Вс. C 10:00 до 24:00, заказы принимаются до 23:30.
	</td>
</tr>
</tbody>
</table>
<p>
	 Думаете, бесплатная доставка суши – это фантастика? А SUSHMAN &amp; PIZZMAN готовит вкуснейшие суши и действительно доставляет их во все существующие районы Новосибирска – бесплатно!
</p>
<p>
	 Получить свой вкусный заказ и не потратить ни рубля на доставку? Легко! Выберите свою любимую еду, чтобы сумма чека превышала 400 рублей – и платите лишь за еду! А привезут ваши суши по любому адресу в подарок за выбор «Сушман и Пиццман».
</p>
<p>
	 Впрочем, не ограничивайте желаний – для вас ещё бесплатная доставка пиццы и всего, что увидите в нашем меню. Просто закажите на 400 рублей – а SUSHMAN &amp; PIZZMAN сделает вам вкусно!
</p>
 <br><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>