#include <iostream>
#include <string>
#include <cstring>
#include <vector>
#include <fstream>
#include <algorithm>
#include "BWT.h"
using namespace std;

typedef unsigned char byte;
BWT::BWT() {}
void BWT::rar() {
	Indexes.clear();
	for (int i = 0; i < Length; i++) {
		Indexes.push_back(i);
	}
	int source = SortMatrix2();
	int mass[4] = {};
	for (int i = 3; i > -1; i--) {
		if (source) {
			mass[i] = source % 10;
			source /= 10;
		}
	}
	write(mass);
}

int BWT::SortMatrix2() {
	int source = 0;
	vector<int> count(Length);
	for (int i = 0; i < Length; i++)
		count[FileContent[i]]++;

	vector<int> from(Length);
	for (int i = 1; i < Length; i++)
		from[i] = from[i - 1] + count[i - 1];

	for (int i = 0; i < Length; i++)
		Indexes[from[FileContent[i]]++] = i;

	vector<int> groups(Length);
	int groupsCount = 1;
	groups[Indexes[0]] = 0;
	for (int i = 1; i < Length; i++) {
		if (FileContent[Indexes[i]] != FileContent[Indexes[i - 1]])
			groupsCount++;
		groups[Indexes[i]] = groupsCount - 1;
	}

	vector<int> newIndexes(Length), newGroups(Length);

	for (int blockLength = 1; blockLength < Length; blockLength *= 2) {
		for (int i = 0; i < Length; i++)
			newIndexes[i] = (Indexes[i] + Length - blockLength) % Length;

		for (int i = 0; i < Length; i++)
			count[i] = from[i] = 0;

		for (int i = 0; i < Length; i++)
			count[groups[newIndexes[i]]]++;

		for (int i = 1; i < groupsCount; i++)
			from[i] = from[i - 1] + count[i - 1];

		for (int i = 0; i < Length; i++) {
			if (newIndexes[i] == 0)
				source = from[groups[newIndexes[i]]];
			Indexes[from[groups[newIndexes[i]]]++] = newIndexes[i];
		}

		groupsCount = 1;
		newGroups[Indexes[0]] = 0;
		for (int i = 1; i < Length; i++) {
			if (groups[Indexes[i]] != groups[Indexes[i - 1]] ||
				groups[(Indexes[i] + blockLength) % Length] != groups[(Indexes[i - 1] + blockLength) % Length])
				groupsCount++;
			newGroups[Indexes[i]] = groupsCount - 1;
		}

		for (int i = 0; i < Length; i++)
			groups[i] = newGroups[i];
	}
	return source;
}

void BWT::write(int source[]) {
	for (int i = 0; i < 4; i++)
		g << (char)source[i];
	for (int i = 0; i < Length; i++) {
		g << FileContent[(Indexes[i] - 1 + Length) % Length];
	}
}

void BWT::encode(string file) {
	Length = 9999;
	f.open("C:/Users/Pavel/Desktop/rar/" + file, ios::out | ios::binary);
	g.open("C:/Users/Pavel/Desktop/rar/encodeBWT.bzip2", ios::out | ios::binary);
	char ch;
	while (1) {
		FileContent.clear();
		for (int i = 0; i < 9999; i++) {
			ch = f.get();
			if (f.eof()) {
				Length = FileContent.size();
				rar();
				f.close();
				g.close();
				return;
			}
			FileContent.push_back(ch);
		}
		rar();
	}
}

void BWT::decode(string extension) {
	f.open("C:/Users/Pavel/Desktop/rar/decodeMTF.bzip2", ios::out | ios::binary);
	g.open("C:/Users/Pavel/Desktop/rar/decodeBWT." + extension, ios::out | ios::binary);
	char ch;
	int size = 9999;
	while (1) {
		if (f.eof()) {
			g.close();
			f.close();
			return;
		}
		vector<byte> bufIn;
		int ko = 1000;
		int primaryIndex = 0;
		for (int i = 3; i > -1; i--) {
			primaryIndex += ko * (int)f.get();
			ko /= 10;
		}

		if (f.eof()) {
			g.close();
			f.close();
			return;
		}
		for (int i = 0; i < 9999; i++) {
			ch = f.get();
			if (f.eof()) {
				size = bufIn.size();
				break;
			}
			bufIn.push_back(ch);
		}
		vector<byte> F(size);
		vector<int> buckets(256, 0);
		int i, j, k;
		vector<int> indices(size);
		for (i = 0; i < size; i++)
			buckets[bufIn[i]]++;
		for (i = 0, k = 0; i < 256; i++)
			for (j = 0; j < buckets[i]; j++)
				F[k++] = i;

		for (i = 0, j = 0; i < 256; i++) {
			while (j < size && i > F[j])
				j++;
			buckets[i] = j;
		}
		for (i = 0; i < size; i++)
			indices[buckets[bufIn[i]]++] = i;
		for (i = 0, j = primaryIndex; i < size; i++) {
			j = indices[j];
			g << bufIn[j];
		}
	}
	g.close();
	f.close();
}