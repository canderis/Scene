/*MySQL Script for creating all the tables
needed for Scene (app)*/

/*Create table User_Info*/
CREATE TABLE IF NOT EXISTS User_Info (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(40) NOT NULL,
	password VARCHAR(255) NOT NULL,
    fb_user_id VARCHAR(30),
    email_address VARCHAR(255),
    account_type INT(1) NOT NULL,
    PRIMARY KEY (id)
);

/*Create table User_Opinion*/
CREATE TABLE IF NOT EXISTS User_Opinion (
    id INT NOT NULL AUTO_INCREMENT,
    user_info_id INT NOT NULL,
    scene_id VARCHAR(255) NOT NULL,
    scene_user_interest INT(1) NOT NULL DEFAULT 0,
	scene_user_like INT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    FOREIGN KEY (user_info_id)
        REFERENCES User_Info (id)
);

/*Drop statements, if needed.
Must drop User_Opinion first, because it's foreign
key is in the User_info table*/
/*DROP TABLE if exists User_Opinion;
DROP TABLE if exists User_Info;*/