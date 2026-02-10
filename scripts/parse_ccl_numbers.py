#!/usr/bin/env python3
"""
Parse CCL Session Schedule from an Apple Numbers file.
Outputs JSON array of sessions: each has day (1|2), presenter_name, co_presenter_name,
session_title, description, max_participants, special_equipment, email, co_presenter_email.
Run: python3 scripts/parse_ccl_numbers.py public/CCL_Session_Schedule_March_2026.numbers
"""
import json
import sys

def main():
    if len(sys.argv) < 2:
        print("Usage: python3 parse_ccl_numbers.py <path-to-.numbers-file>", file=sys.stderr)
        sys.exit(1)
    path = sys.argv[1]
    try:
        from numbers_parser import Document
    except ImportError:
        print('{"error": "Install numbers-parser: pip install numbers-parser"}', file=sys.stderr)
        sys.exit(1)
    doc = Document(path)
    out = []
    day = 0
    for sheet in doc.sheets:
        day += 1
        for table in sheet.tables:
            for row in range(2, table.num_rows):  # skip header rows
                cells = [table.cell(row, c).value for c in range(table.num_cols)]
                if len(cells) < 8:
                    cells.extend([None] * (8 - len(cells)))
                presenter, co_presenter, title, desc, max_part, equipment, email, co_email = cells[:8]
                if not title or not str(title).strip():
                    continue
                # Normalize email typo
                if email and '@@' in str(email):
                    email = str(email).replace('@@', '@')
                out.append({
                    "day": day,
                    "presenter_name": (presenter or "").strip() or None,
                    "co_presenter_name": (co_presenter or "").strip() or None,
                    "session_title": (title or "").strip(),
                    "description": (desc or "").strip() or None,
                    "max_participants": (max_part or "").strip() or None,
                    "special_equipment": (equipment or "").strip() or None,
                    "email": (email or "").strip() or None,
                    "co_presenter_email": (co_email or "").strip() or None,
                })
    print(json.dumps(out, indent=2))

if __name__ == "__main__":
    main()
