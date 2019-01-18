FROM ubuntu:18.04
ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update && \
apt-get install -y \
tzdata freeradius freeradius-mysql vim
RUN rm -rf /var/lib/apt/lists/*
COPY --chown=freerad:freerad freeradius/3.0/policy.d/cmu-wifi /etc/freeradius/3.0/policy.d/cmu-wifi
COPY --chown=freerad:freerad freeradius/3.0/radiusd.conf /etc/freeradius/3.0/radiusd.conf
COPY --chown=freerad:freerad freeradius/3.0/sites-available/default /etc/freeradius/3.0/sites-available/default
COPY --chown=freerad:freerad freeradius/3.0/mods-config/sql/main/mysql/queries.conf /etc/freeradius/3.0/mods-config/sql/main/mysql/queries.conf
COPY --chown=freerad:freerad freeradius/3.0/mods-available/sql /etc/freeradius/3.0/mods-available/sql
RUN cd /etc/freeradius/3.0/mods-enabled/ && ln -sf ../mods-available/sql sql
EXPOSE 1812/udp
EXPOSE 1813/udp
CMD ["freeradius","-f"]
