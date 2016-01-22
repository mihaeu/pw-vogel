# PW Vogel

## Getting started

```bash
git clone https://github.com/mihaeu/pw-vogel
cd pw-vogel

make
make cov
make testdox
```

## Testdox

```
Email
 [x] Accepts valid email
 [x] Does not accept invalid email

Message
 [x] Limit is 80 characters
 [x] Normal message is accepted
 [x] Can set time of creation

Nickname
 [x] Accepts valid nickname
 [x] Rejects too short nickname
 [x] Rejects too long nickname

User
 [x] Receives messages from following users
 [x] Unfollowed user does not receive messages
 [x] Output for empty timeline
 [x] Can view timeline
 [x] No messages on timeline from unfollowed users
 [x] Cannot follow user when blacklisted
 [x] Cannot receive from users who are not friends
 [x] Users with same email are equal
 [x] Users with different emails are not equal
```
