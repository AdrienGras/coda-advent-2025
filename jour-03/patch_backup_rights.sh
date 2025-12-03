#!/bin/bash

# -- defining script parameters --

# set
# - treat unset variables as an error
# - execution trace (used to avoid echo spam, and detect bad behavior)
set -ux

# -- variables --
TARGET_USER="pere-noel";
TARGET_FILE="./backup.sh";

# -- surface verification (early exit conditions) --

# check if the target file exists
if [ ! -f "${TARGET_FILE}" ]; then
  echo "Error: Target file '${TARGET_FILE}' does not exist. aborting...";
  exit 1;
fi

# check if current user is root
#
# assuming this is run on a standard linux system
# and permissions in this directory are kinda messed up
# we need root rights to change file ownership and permissions
#
# /!\ this is bad practice in real life, and should never be
# used in production environments, use ACL or system-trusted rights instead  /!\
if [ "$(id -u)" -ne 0 ]; then
  echo "Error: This script must be run as root. aborting...";
  exit 1;
fi

# -- main script logic --

# change ownership of the target file to the target user
chown "${TARGET_USER}:${TARGET_USER}" "${TARGET_FILE}";

# set read, write and execute permissions for the target user
chmod u+rwx "${TARGET_FILE}";

# set no rights for group and others
chmod go-rwx "${TARGET_FILE}";