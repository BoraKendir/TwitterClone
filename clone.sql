DROP DATABASE IF EXISTS tw_clone;
CREATE DATABASE tw_clone;
USE tw_clone;

CREATE TABLE IF NOT EXISTS `users` (
    `user_id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL,
    `username` varchar(50) NOT NULL,
    `password` varchar(50) NOT NULL,
    `register_date` datetime NOT NULL,
    PRIMARY KEY(`user_id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `tweets` (
    `tweet_id` int NOT NULL AUTO_INCREMENT,
    `tweet_text` varchar(50) NOT NULL,
    `tweet_user_id` int NOT NULL,
    `tweet_date` datetime NOT NULL,
    PRIMARY KEY(`tweet_id`)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS `follows` (
    `follow_id` int NOT NULL AUTO_INCREMENT,
    `follower_id` int NOT NULL,
    `following_id` int NOT NULL,
    `follow_date` datetime NOT NULL,
    PRIMARY KEY(`follow_id`),
    FOREIGN KEY (`follower_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB;

INSERT INTO `users` (`user_id`, `name`, `username`, `password`, `register_date`) VALUES
(1,'Bora','admin','admin',NOW());
INSERT INTO `users` (`user_id`, `name`, `username`, `password`, `register_date`) VALUES
(2,'Ali','core1','123',NOW());
INSERT INTO `users` (`user_id`, `name`, `username`, `password`, `register_date`) VALUES
(3,'Buğra','core2','123',NOW());
INSERT INTO `users` (`user_id`, `name`, `username`, `password`, `register_date`) VALUES
(4,'Can','core3','123',NOW());
INSERT INTO `users` (`user_id`, `name`, `username`, `password`, `register_date`) VALUES
(5,'Doruk','KimseBeniTakipEtmez','123',NOW());

INSERT INTO `tweets` (`tweet_id`, `tweet_text`, `tweet_user_id`, `tweet_date`) VALUES
(1,'first tweet ever',1,NOW());
INSERT INTO `tweets` (`tweet_id`, `tweet_text`, `tweet_user_id`, `tweet_date`) VALUES
(2,'core tweet 1',2,DATE_ADD(NOW(), INTERVAL 10 SECOND));
INSERT INTO `tweets` (`tweet_id`, `tweet_text`, `tweet_user_id`, `tweet_date`) VALUES
(3,'core tweet 2',3,DATE_ADD(NOW(), INTERVAL 20 SECOND));
INSERT INTO `tweets` (`tweet_id`, `tweet_text`, `tweet_user_id`, `tweet_date`) VALUES
(4,'core tweet 3',4,DATE_ADD(NOW(), INTERVAL 30 SECOND));

INSERT INTO `follows` (`follow_id`, `follower_id`, `following_id`, `follow_date`) VALUES
(1,2,1,NOW());
INSERT INTO `follows` (`follow_id`, `follower_id`, `following_id`, `follow_date`) VALUES
(2,3,1,NOW());
INSERT INTO `follows` (`follow_id`, `follower_id`, `following_id`, `follow_date`) VALUES
(3,4,1,NOW());
INSERT INTO `follows` (`follow_id`, `follower_id`, `following_id`, `follow_date`) VALUES
(4,1,2,NOW());
INSERT INTO `follows` (`follow_id`, `follower_id`, `following_id`, `follow_date`) VALUES
(5,1,3,NOW());
INSERT INTO `follows` (`follow_id`, `follower_id`, `following_id`, `follow_date`) VALUES
(6,1,4,NOW());

DELIMITER //

CREATE PROCEDURE GetFollowerTweets(IN userId INT)
BEGIN
    SELECT tweets.tweet_text, tweets.tweet_date, users.username AS tweet_user
    FROM tweets
    INNER JOIN follows ON tweets.tweet_user_id = follows.following_id
    INNER JOIN users ON tweets.tweet_user_id = users.user_id
    WHERE follows.follower_id = userId
    ORDER BY tweets.tweet_date DESC;
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE GetUserTweets(IN sessionUserId INT)
BEGIN
    SELECT tweet_text, tweet_date
    FROM tweets
    WHERE tweet_user_id = sessionUserId
    ORDER BY tweet_date DESC;
END //

DELIMITER ;