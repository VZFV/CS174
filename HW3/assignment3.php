CREATE DATABASE HW4;
USE HW4;
CREATE TABLE videodata (
camera_id SMALLINT UNSIGNED NOT NULL PRIMARY KEY,
time_stamp TIMESTAMP,
video_path VARCHAR(256),
thumbnail_path VARCHAR(256),
hash_content BINARY(32)
);
# The company may have assigned each camera ID an unique number(e.g. 1,2,3,4....). It must be positive,
# that's why I chosen UNSIGNED and considered it as primary key. It also cannot be null.
# UNSIGNED SMALLINT support an integer up to 65535 that is enough for company storing 100 camera IDs.
# TIMESTAMP is a temporal data type that holds the combination of date and time. It has specific format
# which is YYYY-MM-DD HH:MM:SS, it is the best choice to save the timestamp of the video.
# VARCHAR is suitable for path which may include characters, numbers and letters.
# Since each hash is always 32 bytes, the type must be BINARY and limited to 32.

CREATE TABLE imagedata (
hash_content BINARY(32) NOT NULL,
image_path VARCHAR(256),
image_timestamp TIMESTAMP
);

# Each video content has unique hash. Since each hash is always 32 bytes, the type
# must be BINARY and limited to 32.
# VARCHAR is suitable for path which may include characters, numbers and letters.
# TIMESTAMP is the best choice to save the timestamp of the image taken from the video.
