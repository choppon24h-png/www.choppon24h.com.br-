CREATE TABLE `franchiseLeads` (
	`id` int AUTO_INCREMENT NOT NULL,
	`fullName` varchar(120) NOT NULL,
	`email` varchar(320) NOT NULL,
	`phone` varchar(24) NOT NULL,
	`city` varchar(120) NOT NULL,
	`state` varchar(2) NOT NULL,
	`investmentRange` varchar(40) NOT NULL,
	`preferredModel` varchar(40) NOT NULL,
	`message` text,
	`status` enum('new','contacted','qualified','closed') NOT NULL DEFAULT 'new',
	`consentAt` timestamp NOT NULL DEFAULT (now()),
	`createdAt` timestamp NOT NULL DEFAULT (now()),
	CONSTRAINT `franchiseLeads_id` PRIMARY KEY(`id`)
);
