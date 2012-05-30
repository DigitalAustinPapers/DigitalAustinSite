svn export ./ temp-svn-export
scp -r temp-svn-export/* digi4071@digitalaustinpapers.org:./html/
rm -r temp-svn-export
