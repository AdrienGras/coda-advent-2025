cmake_minimum_required(VERSION 4.0)
project(reindeers C)

set(CMAKE_C_STANDARD 11)

include_directories(.)

# main program
add_executable(reindeers
        main.c)