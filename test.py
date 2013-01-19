
from nltk.tokenize import word_tokenize, wordpunct_tokenize, sent_tokenize, PunktSentenceTokenizer

with open("text/part1.xhtml") as f:
	s = f.read()
	# sentences = sent_tokenize(s)
	print list(PunktSentenceTokenizer().span_tokenize(s))
	# for sentence in sentences:
	# 	print sentence
	# 	print "==="