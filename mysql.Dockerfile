# Install official mysql image
FROM mysql:8

# Set the environment variables for the database
ENV MYSQL_ROOT_PASSWORD=PortfolioAdmin123
ENV MYSQL_DATABASE=it-development-portfolio
ENV MYSQL_USER=portfolio-user
ENV MYSQL_PASSWORD=Portfolio123


