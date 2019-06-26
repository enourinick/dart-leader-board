# Test task (Nodejs/Angular)

1. [the purpose of the project]

## The purpose of the project.

We have a dart game right in the office and we are gonna create a leader board for that game.
What we have are a dart board and an application can communicate intelligently to the dart board. this application is very capable. it can call APIs.

### What do we want

1. People should be able to anonymously check the leader board.
2. People should be able to register/login to play dart via this leader board.
3. Users should be able to create a game and invite or kick other players.
4. Users should be capable to join or leave a game.
5. After each round of game for each player, the dart-board is calling an API in order to send the score to the leader board.
6. Determining who is the game winner.
7. At the end of the day, we should have a way to figure out the score of players. this score is all previous games score summation of a specific player.

### APIs

Lets first deal with definitions of an API access level. an API could be `protected`  or `public`.
`public` means anyone from anywhere should be able to access this API limitlessly. 
but in order to access protected APIs user should send the token-type and token within the request headers.
To do so, you should submit an `Authorization` key with the value of `token-type` following by `token. 

**Example:**
```
Authorization:Bearer eyJ0eXAiOiJKV1QiLCJhbG
```

In the other hand, if you want to get JSON response and send your request in JSON format,
you should add these to your requests' header:

```
Accept:application/json
Content-Type:application/json
```

### User operations

User are capable to **register**, **login**, **get their own information**, **update their information** and view the **index** of all users with their scores.

##### Register

* Address: `\api\user`
* Method: `Post`
* Access level: `Public`
* Request body:
```json
{
    "email": "email@domain.com",
    "password": "a_secret_password",
    "name": "Name of the player"
}
```

> Note 1: Keep in mind, you should use a unique email address

> Note 2: Fill in all fields are mandatory.

##### Login

* Address: `\oaut\token`
* Method: `Post`
* Access level: `Public`
* Request body:
```json
{
    "client_id": 2,
    "client_secret": "your_client_secret",
    "grant_type": "password",
    "username": "user@devolon.fi",
    "password": "123456"
}
```
* Response:
```json
{
    "token_type": "token-type",
    "expires_in": 31622399,
    "access_token": "access_token",
    "refresh_token": "refresh_token"
}
```

##### Who am I?

* Address: `\api\me`
* Method: `Get`
* Access level: `Protected`
* Response:
```json
{
    "name": "Charity Bosco",
    "email": "susanna.hahn@example.net",
    "email_verified_at": "2019-06-26 16:37:54",
    "updated_at": "2019-06-26 16:37:54",
    "created_at": "2019-06-26 16:37:54",
    "id": 797
}
```

##### Update the user's information

* Address: `\api\me`
* Method: `Get`
* Access level: `Protected`
* Response:
```json
{
    "name": "Charity Bosco",
    "email": "susanna.hahn@example.net",
    "password": "Secret"
}
```

> Note: in the update process, none of the values is allowed remain null, but you can try not to send if you do not want to change them.

##### List of users

* Address: `/api/user?page=:page_number`
* Method: `Get`
* Access level: `Public`
* Response:
```json
{
    "current_page": 1,
    "data": [
        {
            "id": 865,
            "name": "Davin Schmidt Jr.",
            "email": "francisca85@example.com",
            "created_at": "2019-06-26 17:28:10",
            "updated_at": "2019-06-26 17:28:10",
            "score": 100
        },
        {
            "id": 866,
            "name": "Zakary Bogan",
            "email": "gvonrueden@example.net",
            "created_at": "2019-06-26 17:28:10",
            "updated_at": "2019-06-26 17:28:10",
            "score": 1234
        }
    ],
    "first_page_url": "https:\/\/domain.com\/api\/user?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "https:\/\/domain.com\/api\/user?page=1",
    "next_page_url": null,
    "path": "https:\/\/domain.com\/api\/user",
    "per_page": 25,
    "prev_page_url": null,
    "to": 2,
    "total": 2
}
```

> Note: the `page` (query string) parameter is an optional parameter.

### Game operations

Any authenticated user is able to **create** a game, right after creating a game, the creator becomes the first member.

Authenticated users who know the game identification should be able to **join** the game.

Game members are able to **update** the game, **invite** other users, **kick** other game members, and **increase their own scores**.

As soon as a user reach the game's `target_score` the game will be finished and we call that use winner of the game.
 
> Note: No one should be able to *update*, *join*, *leave*, *invite*, *kick* and *add score* to a finished game.

##### Create a new game

Address: `/api/game`
Method: `Post`
Request body:
```json
{
    "target_score": 301
} 
```

##### Update a game

* Address: `/api/game/:game_id`
* Method: `Put`
* Request body:
```json
{
    "target_score": 501
} 
```
* Access level: `Protected`

##### View a game:

* Address: `/api/game/:game_id`
* Method: `Get`
* Access level: `Public`
* Response:
```json
{
    "id": 919,
    "created_at": "2019-06-26 17:02:23",
    "updated_at": "2019-06-26 17:02:23",
    "target_score": 459,
    "winner_id": 856,
    "users": [
        {
            "id": 857,
            "name": "Raul Bednar",
            "email": "norbert20@example.net",
            "email_verified_at": "2019-06-26 17:02:23",
            "created_at": "2019-06-26 17:02:23",
            "updated_at": "2019-06-26 17:02:23",
            "score": 0,
            "pivot": {
                "game_id": 919,
                "user_id": 857,
                "score": 0,
                "created_at": "2019-06-26 17:02:23",
                "updated_at": "2019-06-26 17:02:23"
            }
        },
        {
            "id": 858,
            "name": "Ms. Tracy Walker III",
            "email": "baltenwerth@example.org",
            "email_verified_at": "2019-06-26 17:02:23",
            "created_at": "2019-06-26 17:02:23",
            "updated_at": "2019-06-26 17:02:23",
            "score": 0,
            "pivot": {
                "game_id": 919,
                "user_id": 858,
                "score": 0,
                "created_at": "2019-06-26 17:02:23",
                "updated_at": "2019-06-26 17:02:23"
            }
        }
    ],
    "winner": {
        "id": 856,
        "name": "Lexus Macejkovic",
        "email": "clegros@example.net",
        "email_verified_at": "2019-06-26 17:02:23",
        "created_at": "2019-06-26 17:02:23",
        "updated_at": "2019-06-26 17:02:23",
        "score": 0
    }
}
``` 

##### List Of Games:

* Address: `/api/game?page=:page_number`
* Method: `Get`
* Access level: `Public`
* Response:
```json
{
    "current_page": 1,
    "data": [
        {
            "id": 974,
            "created_at": "2019-06-26 17:05:03",
            "updated_at": "2019-06-26 17:05:03",
            "target_score": 310,
            "winner_id": null,
            "winner": null
        },
        {
            "id": 975,
            "created_at": "2019-06-26 17:05:03",
            "updated_at": "2019-06-26 17:05:03",
            "target_score": 432,
            "winner_id": 864,
            "winner": {
                "id": 864,
                "name": "Sabryna Collins",
                "email": "goodwin.claud@example.com",
                "email_verified_at": "2019-06-26 17:05:03",
                "created_at": "2019-06-26 17:05:03",
                "updated_at": "2019-06-26 17:05:03",
                "score": 0
            }
        }
    ],
    "first_page_url": "https:\/\/domain.com\/api\/game?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "https:\/\/domain.com\/api\/game?page=1",
    "next_page_url": null,
    "path": "https:\/\/domain.com\/api\/game",
    "per_page": 25,
    "prev_page_url": null,
    "to": 2,
    "total": 2
}
```

> Note: the `page` (query string) parameter is an optional parameter.

##### Join a game

* Address: `/api/game/:game_id/join`
* Method: `Post`
* Access level: `Protected`
* Request body:
```json
{}
```

> Note: by calling this endpoint the current user (based on token) joins the game. 

##### Leave a game

* Address: `/api/game/:game_id/left`
* Method: `Delete`
* Access level: `Protected`

> Note: by calling this endpoint the current user (based on token) leaves the game.

##### Invite a user to the game

* Address: `/api/game/:game_id/invite`
* Method: `Post`
* Access level: `Protected`
* Request body:
```json
{
    "user_id": 10
}
```

##### Kick a member from game

* Address: `/api/game/:game_id/kick`
* Method: `Post`
* Access level: `Protected`
* Request body:
```json
{
    "user_id": 10
}
```

##### Add to score

* Address: `/api/game/:game_id/score`
* Method: `Post`
* Access level: `Protected`
* Request body:
```json
{
    "score": 10 
}
```

> Note 1: By calling this endpoint the amount of `score` parameter will be added to the current player score.

> Note 2: A player should exactly reach the `target_score` of a game to become the winner.
