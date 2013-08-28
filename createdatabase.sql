CREATE TABLE IF NOT EXISTS 	entities(
	id SERIAL  PRIMARY KEY,
	type integer NOT NULL  -- 0 : user, 1: groups, 2: events
);


CREATE TABLE IF NOT EXISTS users (
	user_id         SERIAL NOT NULL UNIQUE,
  	name_first      VARCHAR(45)  NOT NULL ,
  	name_last       VARCHAR(45)  NOT NULL ,
  	fbid            VARCHAR(15)  PRIMARY KEY,
  	total_score     integer   default 0,
  	total_duration  integer   default 0,
  	num_achievments integer   default 0,
  	entity          integer NOT NULL REFERENCES entities
);

CREATE TABLE IF NOT EXISTS feedback (
	feedback_id SERIAL PRIMARY KEY,
	user_id     integer NOT NULL REFERENCES users(user_id),
	datestamp   DATE default now(),
	gamename    VARCHAR(25) NOT NULL,
	points      integer NOT NULL,
	miss        integer NOT NULL, 
	duration    integer NOT NULL,
	winner      integer NOT NULL,
	level      integer NOT NULL,
	size        integer NOT NULL,
	score       integer NOT NULL
);

CREATE TABLE IF NOT EXISTS highscores(
	user_id  integer NOT NULL REFERENCES users(user_id),
	gamename VARCHAR(80) NOT NULL,
	highscore integer NOT NULL
);

CREATE TABLE IF NOT EXISTS friends (
	friends_id SERIAL PRIMARY KEY,
	from_user integer NOT NULL REFERENCES users(user_id),
	to_user   integer NOT NULL REFERENCES users(user_id),
	status    integer NOT NULL default 0,  -- 0: pending, 1: friends
	UNIQUE(from_user, to_user)

);

CREATE TABLE IF NOT EXISTS groups(
	group_id SERIAL   PRIMARY KEY,
	creator    integer NOT NULL REFERENCES users(user_id),
	name      VarCHAR(20),
	description VARCHAR(140),
	date_created DATE default now(),
	entity          integer NOT NULL REFERENCES entities
);

CREATE TABLE IF NOT EXISTS group_members(
	group_members_id SERIAL PRIMARY KEY,
	group_id integer NOT NULL REFERENCES groups,
	user_id  integer NOT NULL REFERENCES users(user_id),
	status  integer  NOT NULL default 0, -- 0: pending, 1: join
	UNIQUE(user_id, group_id)
);

CREATE TABLE IF NOT EXISTS events(
	event_id SERIAL PRIMARY KEY,
	creator integer NOT NULL REFERENCES users(user_id),
	type_of_participants integer NOT NULL,  -- 0: users, 1: groups
	start_date DATE NOT NULL,
	end_date   DATE NOT NULL,
	reward_text VARCHAR(144),
	entity      integer NOT NULL REFERENCES entities
	
);

CREATE TABLE IF NOT EXISTS event_participants(
	event_participants_id  SERIAL PRIMARY KEY,
	participant            integer  NOT NULL REFERENCES entities,
	event                  integer  NOT NULL REFERENCES events,
	status                 integer  NOT NULL default 0, -- 0: pending, 1: participating
	UNIQUE(event, participant)
);


CREATE TABLE IF NOT EXISTS posts(
	post_id SERIAL PRIMARY KEY,
	post_creator   integer NOT NULL REFERENCES users(user_id),
	posted_to      integer NOT NULL REFERENCES entities,
	post_date      DATE default now(),
	post_text     VARCHAR(250) NOT NULL
);




