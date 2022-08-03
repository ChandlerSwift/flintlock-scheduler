#!/usr/bin/python3

# This imports the data from the earlier, single-week-per-db iteration of the
# scheduler into the new multi-week scheduler.

# wk[1345].db all have the same schema, so this should work on any of those.

import sys
import sqlite3
import datetime

fromdb = sqlite3.connect(sys.argv[1])
with sqlite3.connect(sys.argv[2]) as todb:

    # create new week
    week_properties=(
        datetime.datetime.now(),
        datetime.datetime.now(),
        input("Week name: "),
        input("Week start date (formatted as 2022-07-31 00:00:00): ")
    )
    week_id = todb.execute("INSERT INTO weeks (created_at, updated_at, name, start_date) values (?, ?, ?, ?)", week_properties).lastrowid
    print(f"New week is {week_id}")

    # scouts
    scout_id_map = {}
    for scout in fromdb.execute("SELECT * FROM scouts;"):
        new_scout_id = todb.execute(f"INSERT INTO scouts (created_at, updated_at, first_name, last_name, rank, age, gender, unit, site, subcamp, council, week_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'unknown', {week_id})", scout[1:]).lastrowid
        scout_id_map[scout[0]] = new_scout_id

    # programs
    program_id_map = {}
    for program in fromdb.execute("SELECT id, name FROM programs;"):
        new_id = todb.execute("SELECT id FROM programs WHERE name = ?;", (program[1],)).fetchone()[0]
        program_id_map[program[0]] = new_id

    # sessions
    session_id_map = {}
    for session in fromdb.execute("SELECT * FROM sessions;"):
        new_session_data = (
            session[1],
            session[2],
            program_id_map[session[3]],
            session[4],
            session[5],
        )
        new_session_id = todb.execute(f"INSERT INTO sessions (created_at, updated_at, program_id, start_time, end_time, every_day, week_id) VALUES (?, ?, ?, ?, ?, false, {week_id})", new_session_data).lastrowid
        session_id_map[session[0]] = new_session_id

    # scout_session
    for session in fromdb.execute("SELECT created_at, updated_at, scout_id, session_id FROM scout_session;"):
        todb.execute(f"INSERT INTO scout_session (created_at, updated_at, scout_id, session_id) VALUES (?, ?, ?, ?)", (session[0], session[1], scout_id_map[session[2]], session_id_map[session[3]]))

    # participation_requirements
    pr_id_map = {}
    for pr in fromdb.execute("SELECT id, name FROM participation_requirements;"):
        if pr[1].lower() == "ASI waiver".lower():
            pr = (pr[0], "ATV Waiver")
        new_id = todb.execute("SELECT id FROM participation_requirements WHERE name LIKE ?;", (pr[1],)).fetchone()
        if new_id:
            new_id = new_id[0]
        else:
            new_id = todb.execute("INSERT INTO participation_requirements (name) VALUES (?)", (pr[1],)).lastrowid
        pr_id_map[pr[0]] = new_id

    # participation_requirement_program
    for prp in fromdb.execute("SELECT created_at, updated_at, participation_requirement_id, program_id FROM participation_requirement_program;"):
        todb.execute(f"INSERT INTO participation_requirement_program (created_at, updated_at, participation_requirement_id, program_id) VALUES (?, ?, ?, ?)", (prp[0], prp[1], pr_id_map[prp[2]], program_id_map[prp[3]]))

    # participation_requirement_scout
    for prs in fromdb.execute("SELECT created_at, updated_at, participation_requirement_id, scout_id FROM participation_requirement_scout;"):
        todb.execute(f"INSERT INTO participation_requirement_scout (created_at, updated_at, participation_requirement_id, scout_id) VALUES (?, ?, ?, ?)", (prs[0], prs[1], pr_id_map[prs[2]], scout_id_map[prs[3]]))

    # preferences
    for pref in fromdb.execute("SELECT created_at, updated_at, scout_id, program_id, rank FROM preferences;"):
        todb.execute(f"INSERT INTO preferences (created_at, updated_at, scout_id, program_id, rank) VALUES (?, ?, ?, ?, ?)", (pref[0], pref[1], scout_id_map[pref[2]], program_id_map[pref[3]], pref[4]))

    # change_requests
    for req in fromdb.execute("SELECT * FROM change_requests;"):
        new_req_data = (
            req[1],
            req[2],
            scout_id_map[req[3]],
            program_id_map[req[4]],
            session_id_map[req[5]] if req[5] else None,
            req[6],
            req[7],
            req[8],
        )
        todb.execute(f"INSERT INTO change_requests (created_at, updated_at, scout_id, program_id, session_id, action, status, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", new_req_data)

todb.commit()
fromdb.close()
todb.close()
