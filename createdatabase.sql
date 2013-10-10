CREATE TABLE IF NOT EXISTS 	entities(
	id    SERIAL  PRIMARY KEY,
	type integer NOT NULL  -- 0 : user, 1: groups, 2: events
);


CREATE TABLE IF NOT EXISTS users (
	user_id          SERIAL NOT NULL UNIQUE,
  	name_first       VARCHAR(45)  NOT NULL ,
  	name_last        VARCHAR(45)  NOT NULL ,
  	fbid             VARCHAR(15)  PRIMARY KEY,
  	total_score      integer   default 0,
  	total_duration   integer   default 0,
  	num_achievments  integer   default 0,
  	entity           integer NOT NULL REFERENCES entities
);

CREATE TABLE IF NOT EXISTS feedback (
	feedback_id  SERIAL PRIMARY KEY,
	user_id      integer NOT NULL REFERENCES users(user_id),
	date_created DATE default now(),
	gamename     VARCHAR(25) NOT NULL,
	points       integer NOT NULL,
	miss         integer NOT NULL, 
	duration     integer NOT NULL,
	winner       integer NOT NULL,
	level       integer NOT NULL,
	size        integer NOT NULL,
	score        integer NOT NULL
);

CREATE TABLE IF NOT EXISTS highscores(
    gamename   VARCHAR(80) NOT NULL,
	user_id    integer NOT NULL REFERENCES users(user_id),
	highscore  integer NOT NULL
);

CREATE TABLE IF NOT EXISTS friends (
	friends_id  SERIAL PRIMARY KEY,
	from_user   integer NOT NULL REFERENCES users(user_id),
	to_user     integer NOT NULL REFERENCES users(user_id),
	status      integer NOT NULL default 0,  -- 0: pending, 1: friends
	UNIQUE(from_user, to_user)

);

CREATE TABLE IF NOT EXISTS groups(
	group_id      SERIAL   PRIMARY KEY,
	date_created  DATE default now(),
    name          VarCHAR(20),
	description   VARCHAR(140),
	creator       integer NOT NULL REFERENCES users(user_id),
	entity        integer NOT NULL REFERENCES entities
);

CREATE TABLE IF NOT EXISTS group_members(
	group_members_id   SERIAL PRIMARY KEY,
	group_id integer  NOT NULL REFERENCES groups,
	user_id  integer  NOT NULL REFERENCES users(user_id),
	status  integer   NOT NULL default 0, -- 0: pending, 1: join
	UNIQUE(user_id, group_id)
);

CREATE TABLE IF NOT EXISTS events(
	event_id              SERIAL PRIMARY KEY,
	name                  VarCHAR(20) NOT NULL,	
	reward_text           VARCHAR(144),
	start_date            DATE NOT NULL,
	end_date              DATE NOT NULL,
	date_created          DATE NOT NULL default now(),
	creator               integer NOT NULL REFERENCES users(user_id),	
	type_of_participants  integer NOT NULL,  -- 0: users, 1: groups,
	reward_points         integer NOT NULL,
	entity                integer NOT NULL REFERENCES entities
	
);

CREATE TABLE IF NOT EXISTS event_participants(
	event_participants_id  SERIAL PRIMARY KEY,
	participant            integer  NOT NULL REFERENCES users(user_id),
	group_id               integer            REFERENCES groups,
	event                  integer  NOT NULL REFERENCES events,
	status                 integer  NOT NULL default 1, -- 0: pending, 1: participating
	UNIQUE(event, participant)
);


CREATE TABLE IF NOT EXISTS posts(
	post_id    SERIAL PRIMARY KEY,
	creator    integer NOT NULL REFERENCES users(user_id),
	posted_to  integer NOT NULL REFERENCES entities,
	post_date  DATE default now(),
	post_text  VARCHAR(250) NOT NULL
);

CREATE TABLE IF NOT EXISTS goals(
	goal_id        SERIAL PRIMARY KEY,
	name           VARCHAR(20) NOT NULL,
	game_name      VarCHAR(25) NOT NULL,
	goal_type      integer NOT NULL, -- 0: get score for a specific game, 1: play for a specific time, 2:get score for all games
	threshold      integer NOT NULL,
	reward_points  integer NOT NULL,
	created_for    integer NOT NULL REFERENCES entities,
	achieved_by    integer NOT NULL REFERENCES users(user_id),
	currently      integer NOT NULL DEFAULT 0,
	is_finished    integer NOT NULL DEFAULT 0, -- 0 : not finished, 1:finished
	start_date     DATE NOT NULL,
	end_date       DATE NOT NULL,
	date_created   DATE NOT NULL default now()
);



