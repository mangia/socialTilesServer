CREATE TABLE IF NOT EXISTS users (
	username    VARCHAR(45)  NOT NULL ,
  	name_first VARCHAR(45)  NOT NULL ,
  	name_last   VARCHAR(45)  NOT NULL ,
  	email_id    VARCHAR(100) NOT NULL ,
  	picture     VARCHAR(255) NOT NULL , --DEFAULT '/web/image/default.jpg' ,
  	location    VARCHAR(45)   default 'Earth' ,
  	fbid        VARCHAR(15)  PRIMARY KEY,
  	age             integer   default 0,
  	total_score     integer   default 0,
  	total_duration  integer   default 0,
  	num_achievments integer   default 0
);

CREATE TABLE IF NOT EXISTS friends (
	friends_id SERIAL PRIMARY KEY,
	timestamp TIMESTAMPTZ default now(), 
	from_user integer NOT NULL REFERENCES users,
	to_user   integer NOT NULL REFERENCES users,
	status    integer NOT NULL default 0  -- 0: pending, 1: friends

);

CREATE TABLE IF NOT EXISTS feedback (
	feedback_id SERIAL PRIMARY KEY,
	user_id   integer NOT NULL REFERENCES users,
	timestamp TIMESTAMPTZ default now(),
	gamename  VARCHAR(25) NOT NULL,
	points    integer NOT NULL,
	miss      integer NOT NULL, 
	duration  integer NOT NULL,
	winner    integer NOT NULL,
	level    integer NOT NULL,
	size      integer NOT NULL,
	score     integer NOT NULL
);

CREATE TABLE IF NOT EXISTS groups(
	group_id SERIAL   PRIMARY KEY,
	creator  integer NOT NULL REFERENCES users,
	descrition VARCHAR(140),
	date_created TIMESTAMPZ default now()
);

CREATE TABLE IF NOT EXISTS group_members(
	group_members_id SERIAL PRIMARY KEY,
	group_id integer NOT NULL REFERENCES groups,
	user_id  integer NOT NULL REFERENCES users
);

CREATE TABLE IF NOT EXISTS events(
	event_id SERIAL PRIMARY KEY,
	event_creator integer NOT NULL REFERENCES users,
	type_0f_participants interger NOT NULL  -- 0: users, 1: groups,
	start_date TIMESTAMP NOT NULL,
	end_date   TIMESTAMP NOT NULL,
	description VARCHAR(140)
	
);

CREATE TABLE IF NOT EXISTS event_participants(
	event_participants_id SERIAL PRIMARY KEY,
	user_id               integer  NOT NULL REFERENCES users,
	event_id              interger  NOT NULL REFERENCES events,
	status                interger  NOT NULL default 0 -- 0: pending, 1: not going, 2: going
);

CREATE TABLE IF NOT EXISTS event_goals(
	event_goal_id   SERIAL PRIMARY KEY,
	event_id 	    interger NOT NULL REFERENCES events,
	goal_name       VARCHAR(25) NOT NULL,
	goal_threshold  integer NOT NULL
);

CREATE TABLE IF NOT EXISTS messages(
	message_id SERIAL PRIMARY KEY,
	sender     integer NOT NULL REFERENCES users,
	receiver   integer NOT NULL REFERENCES users,
	send_date  TIMESTAMPZ NOT NULL default now();
	isNew      boolean  default  TRUE 
);

CREATE TABLE IF NOT EXISTS gamelineups(
	gamelineup_id SERIAL PRIMARY KEY,
	gamelineup_creator integer NOT NULL REFERENCES users,
	description VARCHAR(140),
	date_created TIMESTAMPZ default now()
)

CREATE TABLE IF NOT EXISTS gamelineup_games(
	gamelineup_gamesid SERIAL PRIMARY KEY,
	gamelineup interger REFERENCES gamelineups
	gamename VARCHAR(25) NOT NULL
);

CREATE TABLE IF NOT EXISTS posts(
	post_id SERIAL PRIMARY KEY,
	post_creator  integer NOT NULL REFERENCES users
	post_date     TIMESTAMPZ default now(),
	number_like   integer default now(),
	post_text     VARCHAR(250) NOT NULL
);

CREATE TABLE IF NOT EXISTS post_likes(
	post_likes_id SERIAL PRIMARY KEY,
	post_id       integer NOT NULL REFERENCES posts,
	user_id       integer NOT NULL REFERENCES users
);

CREATE TABLE IF NOT EXISTS badges(
	badge_id SERIAL PRIMARY KEY,
	badge_image VARCHAR(80) NOT NULL,
	badge_text  VARCHAR(140) 
);

CREATE TABLE IF NOT EXISTS achievments(
	achievment_id SERIAL PRIMARY KEY,
	goal_text     VARCHAR(25) NOT NULL,
	goal_threshold integer    NOT NULL,
	
);