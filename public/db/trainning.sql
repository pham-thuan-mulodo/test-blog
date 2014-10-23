-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2014 at 11:43 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `trainning`
--

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `author_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `created_gmt` int(11) NOT NULL,
  `modified_gmt` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `author_id` (`author_id`),
  KEY `comment_ibfk_1` (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `content`, `author_id`, `post_id`, `created_gmt`, `modified_gmt`) VALUES
(1, 'It''s an interesting post.', 1, 2, 0, 0),
(2, 'Thanks so much!', 2, 1, 0, 0),
(4, 'What an interesting post!', 4, 2, 0, 0),
(5, 'There is something in your post I don''t understand. Could you explain more?', 3, 8, 0, 0),
(6, 'Great! I hope you will up more posts.', 6, 4, 0, 0),
(7, 'I see something in your post is wrong. Can you check your post again?', 8, 4, 0, 0),
(9, 'Thanks for your post. I learned more new things from this post.', 4, 9, 0, 0),
(10, 'The post is so long. But I think it is very good to view.', 9, 9, 0, 0),
(12, 'I feel happy when I read your post. Thanks.', 21, 3, 1414054048, 0),
(13, 'Yes. I hope you will write more interesting posts. Have a nice day!', 21, 7, 1414054419, 0),
(14, 'Are you crazy?', 30, 16, 1414054638, 0),
(15, 'Haizz. No comment!', 30, 16, 1414054770, 0);

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `outline` text,
  `content` text NOT NULL,
  `author_id` int(11) NOT NULL,
  `created_gmt` int(11) NOT NULL,
  `modified_gmt` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `author_id` (`author_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`id`, `title`, `outline`, `content`, `author_id`, `created_gmt`, `modified_gmt`) VALUES
(1, 'U.S. floats cutting tobacco from part of Pacific trade pact: sources', 'The United States has floated excluding tobacco products from a key section of a 12-nation Pacific trade deal and signaled it may present a formal proposal to trading partners at talks in Australia, sources briefed on the negotiations said.', 'The United States has floated excluding tobacco products from a key section of a 12-nation Pacific trade deal and signaled it may present a formal proposal to trading partners at talks in Australia, sources briefed on the negotiations said. Dropping tobacco from the investor-state dispute settlement, or ISDS, section of the Trans-Pacific Partnership would prevent tobacco companies taking action against any TPP government under those legal protections, for example over health care measures. Marlboro maker Philip Morris International is challenging Australia&#39;s plain packaging laws, which ban branded cigarette packs, under the country&#39;s investment treaty with Hong Kong, arguing that the laws breach intellectual property rights. One source, who has knowledge of the negotiations but asked not to be named because of the sensitivity of the discussions, said the United States had floated the idea of a carve-out for tobacco under ISDS among senior TPP officials. No language had yet been circulated, but the United States signaled it could formally present a proposal during TPP meetings that are under way in Australia, the source said. A spokesman for the U.S. Trade Representative said it did not expect to present such a plan and countries were still debating how to tackle tobacco-related public health issues in the TPP. &#34;The United States has not tabled any new U.S. proposal on tobacco products and is still engaged on congressional and stakeholder consultation on an appropriate approach. We do not expect to table a new proposal in Australia,&#34; he said.', 1, 0, 1414037499),
(2, 'Sample Post 2', 'This is a sample post', 'Up is opinion message manners correct hearing husband my. Disposing commanded dashwoods cordially depending at at. Its strangers who you certainty earnestly resources suffering she. Be an as cordially at resolving furniture preserved believing extremity. Easy mr pain felt in. Too northward affection additions nay. He no an nature ye talent houses wisdom vanity denied. ', 1, 0, 0),
(3, 'What''s up', 'One advanced diverted domestic sex repeated bringing you old.', 'One advanced diverted domestic sex repeated bringing you old. Possible procured her trifling laughter thoughts property she met way. Companions shy had solicitude favourable own. Which could saw guest man now heard but. Lasted my coming uneasy marked so should. Gravity letters it amongst herself dearest an windows by. Wooded ladies she basket season age her uneasy saw.', 4, 0, 0),
(4, 'New the her nor', 'New the her nor case that lady paid read.', 'New the her nor case that lady paid read. Invitation friendship travelling eat everything the out two. Shy you who scarcely expenses debating hastened resolved. Always polite moment on is warmth spirit it to hearts. Downs those still witty an balls so chief so. Moment an little remain no up lively no. Way brought may off our regular country towards adapted cheered. \r\nNew the her nor case that lady paid read. Invitation friendship travelling eat everything the out two. Shy you who scarcely expenses debating hastened resolved. Always polite moment on is warmth spirit it to hearts. Downs those still witty an balls so chief so. Moment an little remain no up lively no. Way brought may off our regular country towards adapted cheered. \r\nNew the her nor case that lady paid read. Invitation friendship travelling eat everything the out two. Shy you who scarcely expenses debating hastened resolved. Always polite moment on is warmth spirit it to hearts. Downs those still witty an balls so chief so. Moment an little remain no up lively no. Way brought may off our regular country towards adapted cheered. \r\n', 2, 0, 0),
(6, 'Vietnam to increase tax on tobacco, alcohols', 'sample outline', 'The government has proposed to increase luxury tax on tobacco to 70 percent in 2016 and 75 percent in 2019, from the current 65 percent, in an effort to reduce smoking rate among men. The proposed increases, part of a draft amendment to the Law on Special Consumption Tax which will be voted upon by the parliament next month, has been criticized by health experts as modest. Earlier, the health ministry proposed a tax raise of at least 85 percent in 2015 and 105 percent in 2018, which it said would be a &#34;win-win solution&#34; that would help reduce smokers, increase tax revenues while does not harm tobacco businesses. Even by increasing tax at such rates, the smoking rate among men would only reduce to 42 percent in 2020 from the current 47.4 percent, shy of the government&#39;s target of 39 percent, the ministry said. The World Health Organization office in Vietnam have suggested lawmakers raise the tax to 105 percent in 2015 and 145 percent in 2018 to achieve that target. However, the finance ministry have opposed the higher tax raise, claiming that it would only fuel tobacco smuggling, which has already deprived the country an estimated VND6.5 trillion annually in tax revenue. Cigarette imports are banned in Vietnam. According to Vietnam&#39;s Steering Committee on Smoking and Health, Vietnamese smokers spent VND45 trillion (US$2.12 billion) on tobacco and the treatment for related diseases in 2012 -- that&#39;s triple the revenue Vietnam took in from tobacco sales that same year. Too low Adjusted for inflation, the government&#39;s proposed luxury tax raise on tobacco would be by roughly 1 percentage point annually between 2016 and 2018, according to Pham Thi Hoang Anh, Country Director of Health Bridge Canada. Compared with the country&#39;s economic growth forecast of at least 5 percent annually during the same period, such a raise is &#34;too low,&#34; Anh told the VnExpress news website. Consequently, domestic demand for tobacco would not decrease and the target of cutting down on smokers would be unattainable, she said.  VnExpress also quoted Luong Ngoc Khue, director of the Health Ministry&#39;s Administration of Health Examination and Treatment, as saying that the government could learn from the last tax raise in 2008. The luxury tax increased by 10 percentage point to 65 percent that year, but that &#34;did not guarantee a reduction of tobacco consumption&#34; in the ensuing years, he said.   Therefore, &#34;the proposed increase of 5 percentage point would be unable to to cut tobacco consumption as expected,&#34; Khue said. Liquor, beer tax raise  The luxury tax amendments, which will be discussed by lawmakers on November 4 and voted upon later the same month, also proposed increasing taxes on alcohols. Accordingly, tax will increase from 50 percent to 65 percent on products containing 20 percent of alcohol and over, and from 25 percent to 35 percent on those containing less than 20 percent of alcohol. Tax on beer will increase from current 50 percent to 55 percent in July 2015, to 60 percent in 2017 and 65 percent in 2018.', 2, 0, 1414036755),
(7, 'Give lady of they such they sure it. Me contained explained my education.', 'Piqued favour stairs it enable exeter as seeing.', 'Piqued favour stairs it enable exeter as seeing. Remainder met improving but engrossed sincerity age. Better but length gay denied abroad are. Attachment astonished to on appearance imprudence so collecting in excellence. Tiled way blind lived whose new. The for fully had she there leave merit enjoy forth. ', 7, 0, 0),
(8, 'On projection apartments', 'On projection apartments unsatiable so if he entreaties appearance. ', 'On projection apartments unsatiable so if he entreaties appearance. Rose you wife how set lady half wish. Hard sing an in true felt. Welcomed stronger if steepest ecstatic an suitable finished of oh. Entered at excited at forming between so produce. Chicken unknown besides attacks gay compact out you. Continuing no simplicity no favourable on reasonably melancholy estimating. Own hence views two ask right whole ten seems. What near kept met call old west dine. Our announcing sufficient why pianoforte.', 5, 0, 0),
(9, 'Are sentiments apartments', 'Are sentiments apartments decisively the especially alteration.', 'Are sentiments apartments decisively the especially alteration. Thrown shy denote ten ladies though ask saw. Or by to he going think order event music. Incommode so intention defective at convinced. Led income months itself and houses you. After nor you leave might share court balls.', 9, 0, 0),
(16, 'French firm denies $2bn commitment to Long Thanh airport claimed by Vietnam official', 'Vietnamese Deputy Minister of Transport Pham Quy Tieu said at an online talk hosted in Hanoi on October 17 that ADPI, a fully owned subsidiary of the Aéroports de Paris Group, has “committed $2 billion in loans” to fund the construction of Long Thanh International Airport. The new terminal is to be located in the eponymous district in Dong Nai Province, around 50km from Ho Chi Minh City. But an ADPI representative said no such commitment exists. This is the second time deputy minister Tieu has had a statement about funding for Long Thanh rejected by the parties involved. Also during the online talk, Tieu said the premiers of Japan and Vietnam had reached an agreement on an Official Development Assistance (ODA) loan of $2 billion for the latter to build the Long Thanh terminal. But Hiroyuki Hayashi, First Secretary of the Embassy of Japan to Vietnam, denied the information the same day, saying the Japanese government “has not decided on investment for the Long Thanh Airport yet.”', 'A French company specializing in airport architecture and engineering has said it did not commit to providing US$2 billion for a megaproject to build a new international airport outside of Ho Chi Minh City, as a local transport official claimed last week.', 21, 1413968398, 0),
(17, 'The problem with Vietnam&#39;s vomitoriums', '', 'On a recent Thursday evening, a team of young professionals from a multinational e-commerce firm decided to celebrate a long week by heading to the Vuvuzela Beer Club on Nguyen Binh Khiem Street. At around 11pm, in the chaos of laughter and music, someone spun around and smashed a mug over the heads of two young men for apparently no reason. Vuvuzela&#39;s management accompanied the two victims to a nearby emergency room and paid nurses to pick bits of glass out of one&#39;s scalp and sew the other up. The perpetrator, whom no one saw, fled without being arrested. Everyone in their party was shocked by the incident. I can&#39;t say I was. Those two young weren&#39;t assaulted. They were beer clubbed.', 21, 1413970874, 0),
(19, 'Emirates spent millions to get its flight attendants on the World Cup pitch', 'Anyone jarred by the sight of uniformed flight attendants distributing', 'Anyone jarred by the sight of uniformed flight attendants distributing the hardware to the World Cup-winning Germans probably never realized how central sports has become to the marketing strategy of Emirates Airline. From horse racing to cricket to pro tennis, Emirates has spent lavishly on becoming one of the world’s biggest sponsors of sports. Spending on soccer—with the global spectacle that is the FIFA World Cup every four years—dwarfs all other sports. The Dubai-based airline sponsors a half-dozen European clubs, including Arsenal, AC Milan, and Real Madrid. “Emirates has arguably become one of the most prominent brands within football,” the company says. The airline flies to all but 10 of the 32 countries in the World Cup. Emirates spent about $100 million over the past four years to be one of FIFA’s six top “partners,” a well-heeled group that included Adidas, Coca-Cola, Hyundai Motor / Kia Motors, Sony, and Visa, according to an estimate from London-based Brand Finance, which works with corporate marketers. “They have made a very strategic investment, and they are kind of ingrained in the games,” says Dave Chattaway, a sports valuation analyst with Brand Finance. “And seeing them last night … you can truly say they are a global brand name.” The other aspect of such heavy spending by an airline speaks directly to its central goal: continued international expansion, particularly in Europe and North America. Emirates’ explosive growth has siphoned passengers and profits from a bevy of European carriers, led by Germany’s Lufthansa, and has drawn sharp complaints from airlines on both sides of the Atlantic that it is subsidized by its home government in Dubai. Emirates’ large orders of Boeing jumbo jets has also been an issue in the current debate over whether Congress should reauthorize the U.S. Export-Import Bank. Four years from now, when Russia hosts the World Cup, Emirates is likely to retain its premier sponsorship role, predicts Eric Smallwood, senior vice president of Philadelphia-based Front Row Marketing Services, a unit of Comcast (CMCSA), noting the dearth of major Russian companies as sponsors in the recent Sochi Olympic Games. And the airline is far from alone in putting ever-bigger dollars behind marquee sports events. Adidas on Monday said it would pay at least $1.3 billion over 10 years to replace Nike (NKE) as the uniform supplier for Manchester United (MANU). Nike said last week that the economics of a future contract did not work for its shareholders. The size of the deal for one club “just shows you the growth of football worldwide,” Smallwood says. “And I don’t see it slowing down in any capacity.”', 21, 1414038719, 1414038875),
(20, 'US$212 mln cable car system to be built to Son Doong Cave', 'The Quang Binh People’s Committee announced on Wednesday that the north-central province has approved the construction of a cable car system to Son Doong Cave- the world’s current  largest- in UNESCO-recognized Phong Nha- Ke Bang National Park.', 'According to Truong An Ninh, spokesperson of the provincial People’s Committee, the province has permitted Sun Group - a local developer of tourism properties - to conduct surveys for the construction of a cable car system to Son Doong. The 10.6km long cable car system, which will cost roughly US$211.8 million, will have four sections and begin at the entrance to Tien Son Grotto.  The first section will run to Tra Ang Bridge on the west leg of the Ho Chi Minh Trail, while the second will span from Tra Ang Bridge to the rear entrance to Son Doong Grotto. The third will run from the rear entrance of Son Doong Grotto to the mouth of the grotto’s second pit. The last section will extend from the mouth to the floor of Son Doong Grotto’s second pit.  According to an exclusive Tuoi Tre (Youth) Newspaper source, the project is part of Sun Group’s Tourism, Service and Resort Complex in Phong Nha- Ke Bang park. The company has submitted preliminary reports on its cable car system surveys to the provincial People’s Committee. In June, provincial authorities  rebutted rumors that it had allowed Sun Group to build a cable car system and a pagoda in and around Son Doong cave amid public and expert concerns over the possible damage to the UNESCO World Heritage site surrounding it. Phong Nha – Ke Bang National Park was recognized as a UNESCO World Heritage site in 2003. The Son Doong Grotto was discovered in 1991 by Ho Khanh, a local, but only became well-known after a group of scientists from the British Cave Research Association, led by Howard and Deb Limbert, explored it in 2009. According to the Limberts, this cave is five times larger than Phong Nha cave, previously considered the biggest in Vietnam. The largest chamber of Son Doong is more than five kilometers long, 200 meters high, and 150 meters wide.', 21, 1414047802, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(254) NOT NULL,
  `password` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `last_login` int(11) NOT NULL,
  `login_hash` varchar(50) NOT NULL,
  `group` int(11) NOT NULL,
  `profile_fields` text NOT NULL,
  `created_gmt` int(11) NOT NULL,
  `modified_gmt` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `username`, `last_login`, `login_hash`, `group`, `profile_fields`, `created_gmt`, `modified_gmt`) VALUES
(1, 'teo@mulodo.com', '123', 'teo', 0, '', 0, '', 0, 0),
(2, 'ti@mulodo.com', 'ti', 'ti', 0, '', 0, '', 0, 0),
(3, 'huy@mulodo.com', '123', 'huy', 0, '', 0, '', 0, 0),
(4, 'hien@mulodo.com', 'So8jPM0k25c9wiPKSW/n6oCFWnToxjP6oquun2PnNcg=', 'hien', 0, '', 0, '', 0, 1413799921),
(5, 'thuy@mulodo.com', '123', 'thuy', 0, '', 0, '', 0, 0),
(6, 'vy@gmail.com', 'vy123', 'vy', 0, '', 0, '', 0, 0),
(7, 'quang@gmail.com', '123', 'quang', 0, '', 0, '', 0, 0),
(8, 'thanh@yahoo.com', 'thanh', 'thanh', 0, '', 0, '', 0, 0),
(9, 'hieu@gmail.com', 'hieu123', 'hieu', 0, '', 0, '', 0, 0),
(10, 'nhat@mulodo.com', 'K0a6gamZ1WRAMveKC9c3rC5CirxEKonoDQUDYWkLPbE=', 'nhat123', 1413943547, '3f70a4b354e6cbd9abfc71bc6f78a5fcffa4e9b3', 0, 'Sample profile', 0, 1413943724),
(20, 'test@gmail.com', 'K0a6gamZ1WRAMveKC9c3rC5CirxEKonoDQUDYWkLPbE=', 'utest2', 1413776134, 'e49c57c4681cbcfbab5bad1b6631fe43fbfa6401', 0, '', 2014, 1413798876),
(21, 'albert@gmail.com', 'K0a6gamZ1WRAMveKC9c3rC5CirxEKonoDQUDYWkLPbE=', 'albert', 1414029229, 'b82a3fd39c5591a2888a5ae8f06185210c7c353f', 0, '', 1413358363, 0),
(22, 'richard@yahoo.com', 'K0a6gamZ1WRAMveKC9c3rC5CirxEKonoDQUDYWkLPbE=', 'richard', 0, '', 0, '', 1413358434, 1413799623),
(23, 'john@gmail.com', 'a17d9d2bad20bb370ea143effe867f45', 'john', 0, '', 0, '', 1413358523, 0),
(24, 'anna@gmail.com', 'a17d9d2bad20bb370ea143effe867f45', 'anna231', 0, '', 0, '', 1413368152, 0),
(27, 'example@gmail.com', '1816ac0b4bf213b0cfaacd48b6127f12', 'example', 0, '', 0, '', 1413429491, 0),
(28, 'example1@gmail.com', '1816ac0b4bf213b0cfaacd48b6127f12', 'example1', 0, '', 0, '', 1413429975, 0),
(29, 'first@gmail.com', 'K0a6gamZ1WRAMveKC9c3rC5CirxEKonoDQUDYWkLPbE=', 'firstlove123', 1970, '', 0, 'Happy, friendly', 1970, 1413962981),
(30, 'shinichi12@mulodo.com', 'K0a6gamZ1WRAMveKC9c3rC5CirxEKonoDQUDYWkLPbE=', 'shinichi', 1414054571, '373df95b261f60da1e75ca602ff7d772a96587c8', 0, 'Hi, I''m a detective.', 1413777330, 1413963631),
(31, 'flower@mulodo.com', 'Ucvp27PnpDltRNgwjkJDW77l+Rk5EECR9WtG9WiH+zA=', 'flower', 1413778764, 'b6933aaad8e1fed6e071fc7d407335e5b40e5d46', 0, '', 1413778206, 0),
(32, 'tuan@mulodo.com', 'K0a6gamZ1WRAMveKC9c3rC5CirxEKonoDQUDYWkLPbE=', 'tuan_pham123', 0, '', 0, '', 1413947218, 0),
(33, '', 't1vZwYXHGIdGGKMrOb8kL1U0xUcqqs/xGv1ny217qSE=', '', 0, '', 0, '', 1413959804, 0),
(34, 'vinh@mulodo.com', 'K0a6gamZ1WRAMveKC9c3rC5CirxEKonoDQUDYWkLPbE=', 'vinh', 0, '', 0, '', 1413960590, 0),
(35, 'vinh@mulodo', 'K0a6gamZ1WRAMveKC9c3rC5CirxEKonoDQUDYWkLPbE=', 'vinh', 0, '', 0, '', 1413961437, 0),
(36, 'loc@gmail.com', 'K0a6gamZ1WRAMveKC9c3rC5CirxEKonoDQUDYWkLPbE=', 'loc', 0, '', 0, '', 1413961722, 0),
(37, 'quan@mulodo.com', 'K0a6gamZ1WRAMveKC9c3rC5CirxEKonoDQUDYWkLPbE=', 'minhquan', 0, '', 0, '', 1414031338, 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
