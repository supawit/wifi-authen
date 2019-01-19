ARG from=ubuntu:16.04
FROM ${from} as build
ENV DEBIAN_FRONTEND=noninteractive
#
#  Install build tools
#
RUN apt-get update
RUN apt-get install -y devscripts equivs git quilt gcc tzdata

#
#  Create build directory
#
RUN mkdir -p /usr/local/src/repositories
WORKDIR /usr/local/src/repositories

#
#  Shallow clone the FreeRADIUS source
#
ARG source=https://github.com/FreeRADIUS/freeradius-server.git
ARG release=v3.0.x

RUN git clone --depth 1 --single-branch --branch ${release} ${source}
WORKDIR /usr/local/src/repositories/freeradius-server

#
#  Install build dependencies
#
RUN git checkout ${release}; \
    if [ -e ./debian/control.in ]; then \
        debian/rules debian/control; \
    fi; \
    echo 'y' | mk-build-deps -irt'apt-get -yV' debian/control

#
#  Build the server
#
RUN make -j2 deb

#
#  Clean environment and run the server
#
FROM ${from}
COPY --from=build /usr/local/src/repositories/*.deb /tmp/

RUN apt-get update \
    && apt-get install -y /tmp/*.deb \
    && apt-get clean \
    && rm -r /var/lib/apt/lists/* /tmp/*.deb \
    \
    && ln -s /etc/freeradius /etc/raddb

COPY --chown=freerad:freerad cmu-wifi /etc/freeradius/policy.d/cmu-wifi
COPY --chown=freerad:freerad radiusd.conf /etc/freeradius/radiusd.conf
COPY --chown=freerad:freerad default /etc/freeradius/sites-available/default
COPY --chown=freerad:freerad queries.conf /etc/freeradius/mods-config/sql/main/mysql/queries.conf
COPY --chown=freerad:freerad sql /etc/freeradius/mods-available/sql
RUN cd /etc/freeradius/mods-enabled/ && ln -sf ../mods-available/sql sql

EXPOSE 1812/udp 1813/udp
CMD ["freeradius","-f"]