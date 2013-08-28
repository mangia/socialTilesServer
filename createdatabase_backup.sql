CREATE TABLE IF NOT EXISTS users (
	username    VARCHAR(45)  NOT NULL ,
  	name_first VARCHAR(45)  NOT NULL ,
  	name_last   VARCHAR(45)  NOT NULL ,
  	fbid        VARCHAR(15)  PRIMARY KEY,
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
	rewardtext VARCHAR(140);
	
);

CREATE TABLE IF NOT EXISTS event_participants(
	event_participants_id SERIAL PRIMARY KEY,
	user_id               integer  NOT NULL REFERENCES users,
	event_id              interger  NOT NULL REFERENCES events,
	status                interger  NOT NULL default 0 -- 0: pending, 1: not going, 2: going
);



CREATE TABLE IF NOT EXISTS posts(
	post_id SERIAL PRIMARY KEY,
	post_creator  integer NOT NULL REFERENCES users
	post_date     TIMESTAMPZ default now(),
	post_to
	post_text     VARCHAR(250) NOT NULL
);



